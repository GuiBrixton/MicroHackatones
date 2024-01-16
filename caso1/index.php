<html>

<head>
  <title>MicroHackatones / CASO 01</title>
  <style type="text/css">
    body {
      color: rgb(200, 200, 200);
      background: rgb(0, 0, 0);
    }

    div.base {
      width: 1024px;
      height: 768px;
      position: relative;
      background: rgb(40, 40, 50);
      margin: auto;
      margin-top: 80px;
    }

    #murcielago {
      position: absolute;
      top: 300px;
      left: 480px;
      text-align: center;
    }

    #murcielagoV {
      position: absolute;
      top: 300px;
      left: 480px;
      visibility: hidden;
      text-align: center;
    }
  </style>
  <script language="javascript">
    var ns6 = document.getElementById && !document.all ? 1 : 0;
    var resolucion = new Array();
    document.onmousemove = leerRaton;
    var x = 350;
    var y = 250;
    var murcielago;
    var posicionY = 844;

    // Get the horizontal position of the mouse
    function getMouseXPos(e) {
      if (document.layers || ns6) {
        return parseInt(e.pageX)
      } else {
        return (parseInt(event.clientX) + parseInt(document.body.scrollLeft))
      }
    }
    // Get the vertical position of the mouse
    function getMouseYPos(e) {
      if (document.layers || ns6) {
        return parseInt(e.pageY)
      } else {
        return (parseInt(event.clientY) + parseInt(document.body.scrollTop))
      }
    }

    function leerRaton(e) {
      posicionRaton = [getMouseXPos(e), getMouseYPos(e)];
    }

    function movimientoMurcielago() {
      var i = 1;
      if (x != posicionRaton[0]) {
        posicionRaton[0] - 550 > x ? x = x + i : x = x - i;
        murcielago.left = x;
      }
      if (y != posicionRaton[1]) {
        posicionRaton[1] - 200 > y ? y = y + i : y = y - i;
        murcielago.top = y;
      }
    }
    function persecucion() {
      document.getElementById("murcielago").style.visibility = 'hidden';
      murcielago = document.getElementById("murcielagoV").style;
      murcielago.visibility = 'visible';
      setInterval(movimientoMurcielago, 10);
    }
  </script>
</head>

<body onload="leerRaton;">
  <div class="base">
    <div id="murcielagoV"><img src="../resources/imgs/murcielago.gif"><BR><?php echo $_SERVER['HOSTNAME']; ?></div>
    <div id="murcielago"><?php echo $_SERVER['HOSTNAME']; ?><BR><img src="../resources/imgs/murcielago.png" onMouseover="persecucion();">
    </div>
    <div>
</body>

</html>