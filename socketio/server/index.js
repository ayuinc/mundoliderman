import http from 'http'
import express from 'express'
import socketio from 'socket.io'

const app = express()
const server = http.createServer(app)
const io = socketio(server)
const port = process.env.PORT || 9301

io.on('connection', (socket) => {
	console.log(`Connected ${socket.id}`)

	socket.on('status', (data) => {
		socket.broadcast.emit('status', data);
	})

	socket.on('comment', (data) => {
		socket.broadcast.emit('comment', data);
	})

	socket.on('like', (data) => {
		socket.broadcast.emit('like', data);
	})

	socket.on('statusSolvedChange', (data) => {
		socket.broadcast.emit('statusSolvedChange', data);
	})
})

server.listen(port, () => console.log(`Server listening on port ${port}`))

