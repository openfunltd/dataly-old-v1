function renderTooltipContainer(legislatorID) {

}

const tooltips = Array.from(document.querySelectorAll(".wiki-tooltip"));
const tooltipContainer = document.querySelector(".tooltip-container");

tooltips.forEach((tooltip) => {
  tooltip.addEventListener("mouseenter", (e) => {

    legislatorID = e.target.getAttribute('legislator-id');
    tooltipContainer.classList.add("fade-in");
    tooltipContainer.style.left = `${e.pageX}px`;
    tooltipContainer.style.top = `${e.pageY}px`;
    renderTooltipContainer(legislatorID);
  });

  tooltip.addEventListener("mouseleave", (e) => {
    tooltipContainer.classList.remove("fade-in");
  });
});

tooltipContainer.addEventListener('mouseenter', (e) => {
    tooltipContainer.classList.add("fade-in");
})
tooltipContainer.addEventListener('mouseleave', (e) => {
    tooltipContainer.classList.remove("fade-in");
})
