/*
  The following functions enable quick scroll animations to designated elements by id and back to the top of the page
  To call these methods in html:
  <a href="#" onclick="return false;" onmousedown="resetScroller('*****ELEMENT ID GOES HERE*****')">
*/
var scrollY = 0;
var distance = 40;
var speed = 15;

function autoScrollTo(element)
{
  var currentY = window.pageYOffset; //the exact number of pixels the user has scrolled down into the page
  var targetY = document.getElementById(element).offsetTop; //how far the element is from the top of its parent element i.e Ylocation
  //the next two vars will stop the scrolling animation when the desired location has been reached
  var bodyHeight = document.body.offsetHeight;//the height of the body element
  var yPos = currentY + window.innerHeight;
  //the next var will animate the current function several times per second
  var animator = setTimeout('autoScrollTo(\'' + element + '\')',speed); //the function to run and the speed to run it in milliseconds

  if(yPos > bodyHeight)//if the yPosition exceeds the length of the body element, stop animating
  {
    clearTimeout(animator);
  }
  else //otherwise, continue to animate until the target has been reached
  {
    if(currentY < (targetY - distance))
    {
      scrollY = currentY + distance;
      window.scroll(0,scrollY);//this does the actual scrolling but it snaps to its destination so we give it small increments to move to
    }
    else
    {
      clearTimeout(animator);
    }
  }
}

function resetScroller(element)
{
  var currentY = window.pageYOffset; //the exact number of pixels the user has scrolled down into the page
  var targetY = document.getElementById(element).offsetTop; //how far the element is from the top of its parent element i.e Ylocation
  //the next var will animate the current function several times per second
  var animator = setTimeout('resetScroller(\'' + element + '\')',speed); //the function to run and the speed to run it in milliseconds

  if(currentY > targetY)
  {
    scrollY = currentY - distance;
    window.scroll(0,scrollY);//this does the actual scrolling but it snaps to its destination so we give it small increments to move to
  }
  else
  {
    clearTimeout(animator);
  }

}
