async function renderTooltipContainer(legislatorID) {
  if (legislatorID === "") { return; }
  data = localStorage.getItem(legislatorID);
  data = (data == null) ? await requestLegislatorData(legislatorID) : JSON.parse(data);
  $('.tooltip-name').text(data.name);
  $('.tooltip-area').text(data.areaName);
  $('.tooltip-img').attr('src', data.imgUrl);
  htmlContent = data.experience.map(function(text) {
    return '<p>' + text + '</p>'
  }).join('');
  $('.tooltip-experience').html(htmlContent);
}

function requestLegislatorData(legislatorID) {
  return new Promise((resolve, reject) => {
    const url = `https://ly.govapi.tw/legislator/${legislatorID}`;
    $.get(`https://ly.govapi.tw/legislator/${legislatorID}`, function(data) {
      name = data.name;
      areaName = data.legislators[0].areaName;
      experience = data.legislators[0].experience.slice(0, 3);
      imgUrl = data.legislators[0].picUrl;
      data = {
        'name': name,
        'areaName': areaName,
        'experience': experience,
        'imgUrl': imgUrl,
      }
      localStorage.setItem(legislatorID, JSON.stringify(data));
      resolve(data);
    });
  });
}


const tooltips = Array.from(document.querySelectorAll(".wiki-tooltip"));
const tooltipContainer = document.querySelector(".tooltip-container");

tooltips.forEach((tooltip) => {
  tooltip.addEventListener("mouseenter", async (e) => {

    legislatorID = e.target.getAttribute('legislator-id');
    await renderTooltipContainer(legislatorID);
    tooltipContainer.classList.add("fade-in");
    tooltipContainer.style.left = `${e.pageX}px`;
    tooltipContainer.style.top = `${e.pageY}px`;
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
