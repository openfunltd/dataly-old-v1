async function renderTooltipContainer(legislatorID) {
  if (legislatorID === "") { return; }
  data = null;
  data = (data == null) ? await requestLegislatorData(legislatorID) : JSON.parse(data);
  $('.tooltip-name').text(data.name).attr('href', `/legislator/${data.bioId}`);
  $('.tooltip-area').text(data.areaName);
  $('.tooltip-img').attr('src', data.imgUrl);
  htmlContent = data.committee.map(function(text) {
    return '<p>' + text + '</p>'
  }).join('');
  $('.tooltip-committee').html(htmlContent);
}

function requestLegislatorData(legislatorID) {
  return new Promise((resolve, reject) => {
    const url = `https://ly.govapi.tw/v1/legislator/${legislatorID}`;
    $.get(`https://ly.govapi.tw/v1/legislator/${legislatorID}`, function(data) {
      name = data.name;
      legislator = data.legislators.reduce(function(prev, curr) {
        return prev.term >= curr.term ? prev : curr
      });
      areaName = legislator.areaName;
      committee = legislator.committee;
      imgUrl = legislator.picUrl;
      bioId = legislator.bioId;
      data = {
        'name': name,
        'areaName': areaName,
        'committee': committee,
        'imgUrl': imgUrl,
        'bioId': bioId,
      }
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
