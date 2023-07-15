const express = require('express');
const app = express();
const http = require('http');
const server = http.createServer(app);
const { Server } = require('socket.io');
const io = new Server(server);
var Redis = require('ioredis');
var redis = new Redis();
const users = {};
const conversations = {};

io.on('connection', (socket) => {
    console.log('User ID connect :' + socket.id);
    socket.on("conversations", function(data){
        data.conversations.forEach(async (conversation_id) => {
            if(!conversations[conversation_id]){
                conversations[conversation_id] = [];
            }
            conversations[conversation_id].push(socket); 
        });
    })

    socket.on('disconnecting', function(){
        console.log('User ID disconnecting ' + socket.id);
        Object.keys(conversations).forEach(async (conversation_id) => {
            conversations[conversation_id] = await conversations[conversation_id].filter(function(socket_in_conversation) {
                return socket_in_conversation.id !== socket.id;
            });
            if(conversations[conversation_id].length == 0){
                delete conversations[conversation_id];
            }
        })
    })

    socket.on('disconnect', function(){
        console.log('User ID disconnect ' + socket.id);
    })
});

redis.subscribe('laravel_database_new-message');

redis.on('message', function(channel, message) {
    let data = JSON.parse(message);
    conversations[data.conversation_id].map((socket) => {
        socket.emit("message", data)
    })
});

server.listen(3000, () => {
    console.log('listening on 3000');
});