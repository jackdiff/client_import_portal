import React, { Component, Fragment } from 'react'
import { Table, Select, Container, Button, Segment, Checkbox, Form } from 'semantic-ui-react'
import { observer } from "mobx-react"
import StoreContext from 'store/Context'
import State from 'config/state'

@observer
export default class StructureTable extends Component {
  static contextType = StoreContext
  constructor(props) {
    super(props)
    this.state = {
      includeFirstRow: 0,
    }
    this.handleProcess = this.handleProcess.bind(this)
    this.onIncludeFirstRowChange = this.onIncludeFirstRowChange.bind(this)
  }

  makeRow() {
    const sheet = this.props.prefixKey
    const structure = this.context.importStore.getStructure(sheet)
    if(!structure) {
      return []
    }
    return structure.map(data => {
      return (<Table.Row>
        {data.map(el => <Table.Cell>{el}</Table.Cell>)}
      </Table.Row>)
    })
  }

  handleSelectHeader(sheet, id, field) {
    this.context.importStore.setFields(sheet, id, field)
  }

  handleSetField(obj, sheet, id) {
    this.context.importStore.selectField(sheet, id, obj.value)
  }

  onIncludeFirstRowChange(e, obj) {
    this.setState({includeFirstRow: obj.checked ? 1 : 0})
  }

  handleProcess() {
    const includeFirstRow = this.state.includeFirstRow;
    this.context.importStore.upload({includeFirstRow})
  }

  makeHeader() {
    const sheet = this.props.prefixKey
    const structure = this.context.importStore.getHeader(sheet)
    const fields = this.context.importStore.getField(sheet)
    if(structure) {
      return structure.map((key, i) => ( 
      <Table.HeaderCell singleLine key={this.props.prefixKey + i}>
        <Select value={fields[i]} options={this.context.customerStore.fieldOptions} onChange={(e, obj) => this.handleSetField(obj, sheet, i)}/>
      </Table.HeaderCell>))
    }
    return []
    
  }

  render() {
    return (
      <Segment loading={this.context.importStore.state == State.FETCHING}>
        <Table celled padded>
          <Table.Header>
            <Table.Row>
              {this.makeHeader()}
            </Table.Row>
          </Table.Header>

          <Table.Body>
            {this.makeRow()}
          </Table.Body>
        </Table>
        <Container>
          <Form.Field><Checkbox onChange={this.onIncludeFirstRowChange} label='Include first row ? (if it is not a header)' /></Form.Field>
          <Form.Field><Button primary onClick={this.handleProcess} >Process</Button></Form.Field>
        </Container>
      </Segment>
    )
  }
}
