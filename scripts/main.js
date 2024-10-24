function changeCategory(category) {
  const newUrl = new URL(window.location.href);
  newUrl.searchParams.set("category", category);
  window.location.href = newUrl.toString();
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

function filter() {
  let color = document.querySelector("select[name='colors']").value;
  let brand = document.querySelector("select[name='designer']").value;
  let price = document.querySelector("input[type='range']").value;
  let search = document.querySelector("input[name='search']").value;
  const newUrl = new URL(window.location.href);
  newUrl.searchParams.set("color", color);
  newUrl.searchParams.set("brand", brand);
  newUrl.searchParams.set("price", price);
  newUrl.searchParams.set("search", search);
  window.location.href = newUrl.toString();
}

function sortBy(by) {
  const newUrl = new URL(window.location.href);
  newUrl.searchParams.set("sortBy", by);
  window.location.href = newUrl.toString();
}

function cart() {
  // if (document.querySelector("#cartButton").attributes["aria-expanded"].value == "true") {
  document.querySelector("#cartDropDown").classList.toggle("invisible");
  // }
}