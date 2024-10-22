function changeCategory(category) {
  window.location.href = `?category=${category}`;
}

function updateValue(value) {
  document.querySelector("#priceRange").textContent = "$" + value;
}

function showMore(goBack) {
  if (!goBack) {
    const url = new URL(window.location.href);

    let currentPage = parseInt(url.searchParams.get("page")) || 1;

    currentPage += 1;

    url.searchParams.set("page", currentPage);

    window.location.href = url.toString();
  } else {
    const url = new URL(window.location.href);

    let currentPage = parseInt(url.searchParams.get("page")) || 1;

    currentPage -= 1;

    url.searchParams.set("page", currentPage);

    window.location.href = url.toString();
  }
}
