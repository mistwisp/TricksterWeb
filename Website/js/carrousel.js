const carouselSlide = document.querySelector('.carrousel-slide');
const images = document.querySelectorAll('.carrousel-slide img');
const carouselButtons = document.querySelector('.carrousel-buttons');

let counter = 0;
let maxcounter = images.length - 1;
const size = images[0].clientWidth;

carouselSlide.style.transform = 'translateX(' + (-size * counter) + 'px)';

for (let i = 0; i < images.length; i++) {
  const button = document.createElement('carrousel-button');
  button.classList.add('carrousel-button');
  button.textContent = i + 1;
  button.addEventListener('click', () => {
    counter = i;
    carouselSlide.style.transition = 'transform 0.5s ease-in-out';
    carouselSlide.style.transform = 'translateX(' + (-size * counter) + 'px)';
    changeIcon(counter + 1);
  });
  carouselButtons.appendChild(button);
  if(i == 0)
  {
    changeIcon(1);
  }
}

setInterval(() => {
  test = counter++;
  if (test == maxcounter) {
    test = 0;
    counter = 0;
  } else {
    test = counter;
  }
  carouselSlide.style.transition = 'transform 0.5s ease-in-out';
  carouselSlide.style.transform = 'translateX(' + (-size * test) + 'px)';
  changeIcon(test + 1);
}, 5000);

carouselSlide.addEventListener('transitionend', () => {
  if (images[counter].id === 'lastClone') {
    carouselSlide.style.transition = 'none';
    counter = maxcounter;
    carouselSlide.style.transform = 'translateX(' + (-size * counter) + 'px)';
  }
  if (images[counter].id === 'firstClone') {
    carouselSlide.style.transition = 'none';
    counter = 0;
    carouselSlide.style.transform = 'translateX(' + (-size * counter) + 'px)';
  }
});

function changeIcon(icon) 
{
  document.querySelectorAll(".carrousel-button").forEach((icon) => 
  {
    icon.classList.remove("active");
  });
  document.querySelector(".carrousel-button:nth-child(" + icon + ")").classList.add("active");
}