OBJModel obj;
float x, y, z;
float j = 8;
float move = 0;
void setup() {
  size(800, 600, OPENGL);
  obj = new OBJModel();
  obj.load("head.obj");
}

void draw(){
  background(0);
  translate(width/2+50, 200+height/2, 450);
  move = move-0.1;
  document.getElementById('yValue').innerHTML = "" + move;
  ambientLight(100, 100, 100);
  pushMatrix();
  if(obj){
  	 //pushMatrix();
rotateZ(-frameCount/100);  
 //popMatrix();  
    obj.drawMode(POLYGON);
  }
  popMatrix();
  
  hint(ENABLE_DEPTH_TEST);
}

