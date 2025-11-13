const initMap = async (data) => {
  
  const { Map } = await google.maps.importLibrary("maps");
  const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

  const center= { lat: 21.125681, lng: 82.794998 }
  
  const map = new Map(document.getElementById("map"), {
    zoom: 11,
    center,
    mapId: "TFfeeBO7k|tcHB-=",
  });

  // Set map bounds based on station coordinates
  const lngs = data.map((station) => parseFloat(station.longitude));
  const lats = data.map((station) => parseFloat(station.latitude));

  map.fitBounds({
    west: Math.min(...lngs),
    east: Math.max(...lngs),
    north: Math.min(...lats),
    south: Math.max(...lats),
  });

  // Add markers for each station
  data.forEach((station, index) => {
    const marker = new google.maps.marker.AdvancedMarkerElement({
      map,
      content: buildContent(station),
      position: {
        lat: parseFloat(station.latitude),
        lng: parseFloat(station.longitude),
      },
      title: `${station.name} - Point ${index + 1}`,
    });
    marker.addListener("click", () => {
      toggleHighlight(marker, station);
    });
  });
};

const toggleHighlight = (markerView, property) => {
  if (markerView.content.classList.contains("highlight")) {
    markerView.content.classList.remove("highlight");
    markerView.zIndex = null;
  } else {
    markerView.content.classList.add("highlight");
    markerView.zIndex = 1;
  }
};

function buildContent(property) {
  const content = document.createElement("div");

  content.classList.add("property");
  content.innerHTML = `
      <div class="icon">
          <i aria-hidden="true" class="fa fa-icon fa-user" title="user"></i>
          <span class="fa-sr-only">${property.name}</span>
      </div>
      <div class="details">
          <div class="price">${property.name}</div>
          <div class="address">Punch In: ${property.punch_in_time}</div>
          <div class="features">
          <div>
              <i aria-hidden="true" class="fa fa-solid fa-lg fa-globe bed" title="network"></i>
              <span class="fa-sr-only">Network</span>
              <span>${property.mobile_network}</span>
          </div>
          <div>
              <i aria-hidden="true" class="fa fa-lg fa-compass bath" title="bathroom"></i>
              <span class="fa-sr-only">Distance</span>
              <span>${property.traveled_km} km</span>
          </div>
          <div>
              <i aria-hidden="true" class="fa fa-lg fa-road size" title="size"></i>
              <span class="fa-sr-only">Vehicle Type</span>
              <span>${property.vehicle_type}</span>
          </div>
          </div>
      </div>
      `;
  return content;
}

const fetchData = () => {
  console.log('location updated.')
  $.ajax({
    url: `${baseUrl}map/get-pins`,
    method: "GET",
    success: (response) => {
      const data = JSON.parse(response);
      if (data.length > 0) {
        initMap(data);
      }
    },
  });
};