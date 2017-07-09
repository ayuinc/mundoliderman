import http from 'http'
import express from 'express'
import socketio from 'socket.io'
import redis from 'socket.io-redis'

const app = express()
const server = http.createServer(app)
const io = socketio(server)
const port = process.env.PORT || 9300
const redisIP = process.env.REDIS_IP || 'mundoliderman.pus14u.0001.use1.cache.amazonaws.com'
const redisPort = process.env.REDIS_PORT || 6379

io.adapter(redis({ host: redisIP, port: redisPort }))

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

