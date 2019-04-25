import React, { Component, Fragment } from 'react'
import { Progress, Message, Modal, Header, Button, Icon } from 'semantic-ui-react'
import { observer } from "mobx-react"
import StoreContext from 'store/Context'
import State from 'config/state'

@observer 
export default class ImportProgress extends Component {
  static contextType = StoreContext
  constructor(props) {
    super(props)
    this.state = {
    }
  }

  render() {
    return (
      <Fragment>
        <Progress percent={70} indicating label="Processing..." />
        {this.context.importStore.error.fileImport && <Message
          error
          content={this.context.importStore.error.fileImport}
        />}
        {this.context.importStore.error.category && <Message
          error
          content={this.context.importStore.error.category}
        />}
        {this.context.importStore.error.common && <Message
          error
          content={this.context.importStore.error.common}
        />}
        <Modal
          open={this.context.importStore.step == 3 && this.context.importStore.state == State.SUCCESS}
          onClose={() => this.context.importStore.reset()}
          size='small'
        >
          <Header icon='archive' content='Successfully!' />
          <Modal.Content>
            <h3>{this.context.importStore.message}</h3>
          </Modal.Content>
          <Modal.Actions>
            <Button color='green' onClick={() => this.context.importStore.reset()} inverted>
              <Icon name='checkmark' /> Got it
            </Button>
          </Modal.Actions>
        </Modal>
      </Fragment>
    )
  }
}
