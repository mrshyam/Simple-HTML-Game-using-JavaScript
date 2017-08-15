<?php
/**
 * Created by PhpStorm.
 * User: MrShyAm
 * Date: 8/12/2017
 * Time: 6:13 PM
 */
?>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Game | Bouncing ball </title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }
        canvas {
            background: #eee;
            display: block;
            margin: 5% auto;
        }
    </style>
</head>
<body>
<canvas id="myCanvas" width="780" height="520"></canvas>
</body>
</html>
<script>
    var canvas = document.getElementById("myCanvas");
    var ctx = canvas.getContext("2d");

    //Simple collision detection
    var ballRadius = 10;
    var x = canvas.width/2;
    var y = canvas.height-30;
    var dx = 2;
    var dy = -2;

    //Defining a paddle to hit the ball
    var paddleHeight = 10;
    var paddleWidth = 75;
    var paddleX = (canvas.width-paddleWidth)/2;

    //Allowing the user to control the paddle
    var rightPressed = false;
    var leftPressed = false;

    //Setting up the brick variables
    var brickRowCount = 10;
    var brickColumnCount = 3;
    var brickWidth = 70;
    var brickHeight = 20;
    var brickPadding = 5;
    var brickOffsetTop = 30;
    var brickOffsetLeft = 20;
    //Counting the score
    var score = 0;

    //Giving the player some lives
    var lives = 3;

    //Setting up the brick variables
    //Making the bricks disappear after they are hit
    var bricks = [];
    for(c=0; c<brickColumnCount; c++) {
        bricks[c] = [];
        for(r=0; r<brickRowCount; r++) {
            bricks[c][r] = { x: 0, y: 0, status: 1 };
        }
    }

    document.addEventListener("keydown", keyDownHandler, false);
    document.addEventListener("keyup", keyUpHandler, false);
    //Listening for mouse movement
    document.addEventListener("mousemove", mouseMoveHandler, false);

    //Allowing the user to control the paddle
    function keyDownHandler(e) {
        if(e.keyCode == 39) {
            rightPressed = true;
        }
        else if(e.keyCode == 37) {
            leftPressed = true;
        }
    }
    function keyUpHandler(e) {
        if(e.keyCode == 39) {
            rightPressed = false;
        }
        else if(e.keyCode == 37) {
            leftPressed = false;
        }
    }
    //Anchoring the paddle movement to the mouse movement
    function mouseMoveHandler(e) {
        var relativeX = e.clientX - canvas.offsetLeft;
        if(relativeX > 0 && relativeX < canvas.width) {
            paddleX = relativeX - paddleWidth/2;
        }
    }
    //A collision detection function
    //Tracking and updating the status in the collision detection function
    //Listening for mouse movement
    function collisionDetection() {
        for(c=0; c<brickColumnCount; c++) {
            for(r=0; r<brickRowCount; r++) {
                var b = bricks[c][r];
                if(b.status == 1) {
                    if(x > b.x && x < b.x+brickWidth && y > b.y && y < b.y+brickHeight) {
                        dy = -dy;
                        b.status = 0;
                        score++;
                        if(score == brickRowCount*brickColumnCount) {
                            alert("YOU WIN, CONGRATS!");
                            document.location.reload();
                        }
                    }
                }
            }
        }
    }

    function drawBall() {
        ctx.beginPath();
        ctx.arc(x, y, ballRadius, 0, Math.PI*2);
        ctx.fillStyle = "#0095DD";
        ctx.fill();
        ctx.closePath();
    }
    function drawPaddle() {
        ctx.beginPath();
        ctx.rect(paddleX, canvas.height-paddleHeight, paddleWidth, paddleHeight);
        ctx.fillStyle = "#0095DD";
        ctx.fill();
        ctx.closePath();
    }
    //Brick drawing logic
    //Making the bricks disappear after they are hit
    function drawBricks() {
        for(c=0; c<brickColumnCount; c++) {
            for(r=0; r<brickRowCount; r++) {
                if(bricks[c][r].status == 1) {
                    var brickX = (r*(brickWidth+brickPadding))+brickOffsetLeft;
                    var brickY = (c*(brickHeight+brickPadding))+brickOffsetTop;
                    bricks[c][r].x = brickX;
                    bricks[c][r].y = brickY;
                    ctx.beginPath();
                    ctx.rect(brickX, brickY, brickWidth, brickHeight);
                    ctx.fillStyle = "#0095DD";
                    ctx.fill();
                    ctx.closePath();
                }
            }
        }
    }
    //Counting the score
    function drawScore() {
        ctx.font = "16px Arial";
        ctx.fillStyle = "#0095DD";
        ctx.fillText("Score: "+score, 8, 20);
    }
    //Giving the player some lives
    function drawLives() {
        ctx.font = "16px Arial";
        ctx.fillStyle = "#0095DD";
        ctx.fillText("Lives: "+lives, canvas.width-65, 20);
    }

    //Making it move
    //Clearing the canvas before each frame
    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        //A collision detection function
        drawBricks();
        drawBall();
        drawPaddle();
        //Counting the score
        drawScore();
        //Rendering the lives display
        drawLives();
        //Counting the score
        collisionDetection();

        //Letting the paddle hit the ball
        if(x + dx > canvas.width-ballRadius || x + dx < ballRadius) {
            dx = -dx;
        }
        if(y + dy < ballRadius) {
            dy = -dy;
        }
        else if(y + dy > canvas.height-ballRadius) {
            if(x > paddleX && x < paddleX + paddleWidth) {
                dy = -dy;
            }
            else {
               //Giving the player some lives
                lives--;
                if(!lives) {
                    alert("GAME OVER");
                    document.location.reload();
                }
                else {
                    x = canvas.width/2;
                    y = canvas.height-30;
                    dx = 3;
                    dy = -3;
                    paddleX = (canvas.width-paddleWidth)/2;
                }
            }
        }

        //Implementing game over
        if(rightPressed && paddleX < canvas.width-paddleWidth) {
            paddleX += 7;
        }
        else if(leftPressed && paddleX > 0) {
            paddleX -= 7;
        }

        x += dx;
        y += dy;
        //Improving rendering with requestAnimationFrame()
        requestAnimationFrame(draw);
    }

    draw();

</script>
