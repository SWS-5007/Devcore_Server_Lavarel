import express from 'express'
import expressWebsockets from 'express-ws'
import { Server } from '@hocuspocus/server'
import { RocksDB } from '@hocuspocus/extension-rocksdb'

// Configure hocuspocus
const server = Server.configure({
  extensions: [
    new RocksDB({
      // [required] Path to the directory to store the actual data in
      path: './database',

      // [optional] Configuration options for theRocksDB adapter, defaults to "{}“
      options: {
        // This option is only a example. See here for a full list:
        // https://www.npmjs.com/package/leveldown#options
        createIfMissing: true,
      },
    })
  ],
})

// Setup your express instance using the express-ws extension
const { app } = expressWebsockets(express())

// A basic http route
app.get('/', (request, response) => {
  console.log("GET RESPONSE");
  response.send('Hello World!')
})

// Add a websocket route for hocuspocus
// Note: make sure to include a parameter for the document name.
// You can set any contextual data like in the onConnect hook
// and pass it to the handleConnection method.
app.ws('/collaboration/:type/:document', (websocket, request) => {
    console.log(request);
  const context = {
    user: {
      id: 1234,
      name: 'Jane',
    },
  }

  const documentName = `${request.params.type}:${request.params.document}`

  server.handleConnection(websocket, request, documentName, context)
})

// Start the server
app.listen(1234, () => console.log('Listening on http://127.0.0.1:1234'))