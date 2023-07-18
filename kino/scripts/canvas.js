var canvas, c;
var seats = [];
var seatTaken = null;
var m, b, sxg, syg, sx, sy;

for(let i = 0; i < 10; i++){
    seats.push([0,0,0,0,0,0,0,0,0,0]);
}

window.addEventListener('load',_=>
{
    canvas = document.getElementsByTagName('canvas')[0];
    c = canvas.getContext('2d');
    resize();

    canvas.addEventListener('click',e=>
    {
        let x;
        if(e.offsetX < b || e.offsetX > canvas.width-b || e.offsetX > canvas.width/2-m/2 && e.offsetX < canvas.width/2+m/2 || e.offsetY < b)
            return;
        if(e.offsetX > canvas.width/2)
            x = parseInt((e.offsetX - b - m) / sxg);
        else
            x = parseInt((e.offsetX - b) / sxg);
        let y = parseInt((e.offsetY - b) / syg);
        if(!seats[x][y])
        {
            seatTaken = {x:x,y:y};
            document.getElementsByName("row")[0].value = y;
            document.getElementsByName("column")[0].value = x;
            document.getElementById("submit-btn").disabled = false;
        }
        draw();
    });

    draw();
});

window.addEventListener("resize", resize)

function resize()
{
    var container = document.getElementById("container");
    if(container.offsetHeight-30 < innerWidth*0.6*0.7){
        canvas.height = container.offsetHeight-30;
        canvas.width = canvas.height/0.7;
    }
    else{
        canvas.width = innerWidth*0.6;
        canvas.height = canvas.width*0.7;
    }
    b = 0.1 * canvas.width; //Boki
    m = 0.15 * canvas.width; //Środek
    sxg = canvas.width*0.65/10; //Siedzenie X
    syg = 0.06 * canvas.width; //Siedzenie Y
    sx = sxg * 0.8;
    sy = syg * 0.6;
    draw();
}

function draw(){
    c.fillStyle = "#111111";
    c.fillRect(0, 0, canvas.width, canvas.height);
    c.fillStyle = "#AAAAAA";
    c.fillRect(canvas.width*0.20, 0, canvas.width*0.6, canvas.height*0.05);
    c.fillStyle = "rgba(0,0,0,0.15)";
    c.strokeStyle = "black";
    for(let i=0;i<5;i++)
    {
        c.fillRect(canvas.width/2-m/4,canvas.height-b/5*i,m/2,b/5*i);
        c.strokeRect(canvas.width/2-m/4,canvas.height-b/5*i,m/2,b/5*i);
    }
    
    for(let i = 0; i < 10; i++){
        for(let j = 0; j < 10; j++){
            if(!seats[i][j])
                drawChair(m*Math.floor(i/5)+b+sxg*i+(sxg-sx)/2 ,b+syg*j+(syg-sy)/2,'darkred');
            else
                drawChair(m*Math.floor(i/5)+b+sxg*i+(sxg-sx)/2 ,b+syg*j+(syg-sy)/2,'#777777');
        }
    }
    if(seatTaken)
        drawChair(m*Math.floor(seatTaken.x/5)+b+sxg*seatTaken.x+(sxg-sx)/2,b+syg*seatTaken.y+(syg-sy)/2,'green');
}

function drawChair(x,y,color)
{
    c.fillStyle = color;
    c.strokeStyle = "black";
    c.fillRect(x,y,sx*0.8,sy);
    c.strokeRect(x,y,sx*0.8,sy);
    c.beginPath();
    c.moveTo(x, y);
    c.lineTo(x, y+sy);
    c.lineTo(x+sx/4, y+sy);
    c.lineTo(x+sx/4, y);
    c.arc(x+sx/8, y, sx/8, 0, Math.PI, true);
    c.closePath();
    c.fill();
    c.stroke();

    c.beginPath();
    c.moveTo(x+sx, y);
    c.lineTo(x+sx, y+sy);
    c.lineTo(x+sx-sx/4, y+sy);
    c.lineTo(x+sx-sx/4, y);
    c.arc(x+sx-sx/8, y, sx/8, Math.PI, 0);
    c.closePath();
    c.fill();
    c.stroke();

    c.beginPath();
    c.moveTo(x, y+sy);
    c.arc(x+sx/4, y+sy, sx/4, Math.PI, Math.PI*3/2);
    c.lineTo(x+sx-sx/4, y+sy-sx/4);
    c.arc(x+sx-sx/4, y+sy, sx/4, Math.PI*3/2, 2*Math.PI);
    c.lineTo(x, y+sy);
    c.fill();
    c.stroke();
}