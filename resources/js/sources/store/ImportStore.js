import mobx, { observable, computed, action, flow } from "mobx"
import axios from 'axios'
import api, {makeDefaultHeader} from 'config/api'
import State from 'config/state'

const initialSteps = {
  1: {completed: false, disabled: false},
  2: {completed: false, disabled: true},
  3: {completed: false, disabled: true}
}
class ImportStores {
  @observable state = State.IDLE //0/1/2/3
  @observable error = {}
  @observable format = []
  @observable fields = {}
  @observable fileImport = {}
  @observable category = ''
  @observable message = ''
  @observable step = 1
  @observable steps = initialSteps

  @computed
  get getError() {
    return this.error
  }

  @action
  setStep(step) {
    this.step = step
  }

  @action
  reset() {
    this.step = 1;
    this.steps = initialSteps;
    this.error = {}
    this.format = []
    this.category = ''
    this.fileImport = {}
    this.fields = {}
    this.message = ''
  }

  getStructure(sheet) {
    return this.format[sheet].structure
  }

  getHeader(sheet) {
   return this.format[sheet].header 
  }

  getField(sheet) {
    return this.fields[sheet]
  }

  @action
  selectField(sheet, id, field) {
    this.fields[sheet][id] = field
  }

  @action
  fetchStructure = flow(function * (form) {
    if(!form.category) {
      this.error = {'category' : 'No category selected'}
      return
    }
    if(!form.fileImport) {
      this.error = {'fileImport' : 'No file selected'}
      return
    }
    this.format = []
    this.fields = {}
    this.state = State.FETCHING
    try {
      const response = yield this.fetchStructureAPI(form)
      if(response.data.success) {
        this.state = State.SUCCESS
        this.fileImport = form.fileImport
        this.category = form.category
        this.format = response.data.format
        Object.keys(response.data.format).map(sheet => this.fields[sheet] = response.data.format[sheet].header)
        this.step = 2;
        this.steps[1].completed = true;
        this.steps[2].disabled = false;
        this.error = []
      } else {
        this.state = State.ERROR
        this.error = response.data.errors
      }
    } catch (error) {
      this.state = State.ERROR
      this.error = {common: 'Can not connect to server!'}
    }
  })

  @action
  fetchStructureAPI(form) {
    const data = new FormData()
    if(form.category) {
      data.append('category', form.category)
    }
    if(form.fileImport.file) {
      data.append('fileImport', form.fileImport.file)
    }
    

    let header = makeDefaultHeader()
    return axios.post(api.ANALYZE_STRUCTURE, data, {header})
  }

  @action
  upload = flow(function * (form) {
    this.state = State.FETCHING
    this.step = 3;
    this.steps[2].completed = true;
    this.steps[3].disabled = false;
    try {
      const response = yield this.submitUpload(form)
      if(response.data.success) {
        this.state = State.SUCCESS
        this.message = response.data.message
        // this.reset()
      } else {
        this.state = State.ERROR
        this.error = response.data.errors
      }
      
    } catch (error) {
      this.state = State.ERROR
      this.error = {common: 'Can not connect to server!'}
    }
  })

  @action
  submitUpload(form) {
    const data = new FormData()
    data.append('category', this.category)
    data.append('fileImport', this.fileImport.file)
    data.append('fields', JSON.stringify(this.fields))
    data.append('includeFirstRow', form.includeFirstRow)

    let header = makeDefaultHeader()
    return axios.post(api.IMPORT, data, {header});
  }
}

export default new ImportStores
