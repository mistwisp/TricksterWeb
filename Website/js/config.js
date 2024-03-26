document.addEventListener("DOMContentLoaded", function() 
{
	OverlayScrollbars(document.querySelectorAll("body"), { className : "os-theme-dark" });
	OverlayScrollbars(document.getElementById("news-scroll"), { className : "os-theme-dark" });
	OverlayScrollbars(document.getElementById("slider-scroll"), { className : "os-theme-dark" });
});

function toggleMenu() {
  var menu = document.querySelector('.menu');
  menu.classList.toggle('active');
}

function toggleLoginForm() 
{
  var loginForm = document.getElementById( "loginForm" );
  var loginButton = document.getElementById( "loginToggle" );
  if ( loginForm.style.display === "none" || loginForm.style.display === "" ) 
  {
	loginForm.style.display = "block";
	loginButton.classList.add("active");
  } 
  else 
  {
	loginForm.style.display = "none";
	loginButton.classList.remove("active");
  }
}

function changeTab(tab) 
{
	if (tab === 1) 
	{
	  document.getElementById("tab1Content").style.display = "block";
	  document.getElementById("tab2Content").style.display = "none";
	  document.querySelectorAll(".tab").forEach((tab) => 
	  {
		tab.classList.remove("active");
	  });
	  document.querySelector(".tab:nth-child(1)").classList.add("active");
	} 
	else if (tab === 2) 
	{
	  document.getElementById("tab1Content").style.display = "none";
	  document.getElementById("tab2Content").style.display = "block";
	  document.querySelectorAll(".tab").forEach((tab) => 
	  {
		tab.classList.remove("active");
	  });
	  document.querySelector(".tab:nth-child(2)").classList.add("active");
	}
}