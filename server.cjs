const express = require('express')
const app = express()

const server = require('http').createServer(app)
const io = require('socket.io')(server, {
  cors: {
    origin: '*'
  }
})

server.listen(3000, () => {
  console.log('listen on :3000')
})

io.on('connection', (socket) => {
  socket.on('change antrian display', (antrian) => {
    io.emit('change antrian display', antrian)
  })
  socket.on('skip antrian', (skip) => {
    io.emit('skip antrian', skip)
  })
  socket.on('change antrian display loading', (antrian) => {
    io.emit('change antrian display loading', antrian)
  })
  socket.on('change antrian display complete', (antrian) => {
    io.emit('change antrian display complete', antrian)
  })
  socket.on('play current antrian audio', (audioPath) => {
    io.emit('play current antrian audio', audioPath)
  })
})
