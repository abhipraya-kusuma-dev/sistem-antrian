const express = require("express");
const app = express();

const server = require("http").createServer(app);
const io = require("socket.io")(server, {
  cors: {
    origin: "*",
  },
});

server.listen(3000, () => {
  console.log("listen on :3000");
});

io.on("connection", (socket) => {
  console.log("socket connected");

  socket.on("change antrian display", (antrian) => {
    console.log(antrian);
    io.emit("change antrian display", antrian);
  });
  socket.on("change antrian seragam display", (antrian) => {
    io.emit("change antrian seragam display", antrian);
  });

  socket.on("skip antrian", (skip) => {
    console.log(skip);
    io.emit("skip antrian", skip);
  });

  socket.on("change antrian display loading", (antrian) => {
    io.emit("change antrian display loading", antrian);
  });
  socket.on("change antrian display complete", (antrian) => {
    io.emit("change antrian display complete", antrian);
  });

  socket.on("change antrian seragam display loading", (antrian) => {
    io.emit("change antrian seragam display loading", antrian);
  });
  socket.on("change antrian seragam display complete", (antrian) => {
    io.emit("change antrian seragam display complete", antrian);
  });

  socket.on("play current antrian audio", (audioPath) => {
    io.emit("play current antrian audio", audioPath);
  });
  socket.on("play current antrian seragam audio", (audioPath) => {
    io.emit("play current antrian seragam audio", audioPath);
  });

  socket.on("new antrian created", () => {
    console.log("hi there");
    io.emit("new antrian created");
  });
});
