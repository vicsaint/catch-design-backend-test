//local object state that we use in making our request query
const state = {
  page: 1,
  perPage: 25,
  search: "",
  totalPages: 1,
};

//using of DOM to capture the HTML data from the index.php
const list = document.getElementById("list");
const status = document.getElementById("status");
const meta = document.getElementById("meta");
const searchInput = document.getElementById("search");
const perPageSelect = document.getElementById("per-page");
const previousButton = document.getElementById("previous");
const nextButton = document.getElementById("next");

//search timing to avoid debounce
let searchTimer; 

//re-call the api to get the data
async function loadCustomers() {
  status.textContent = "Loading customers...";

  //JS helper working with query string, this will facilitate a nice query request 
  const params = new URLSearchParams({
    page: String(state.page),
    per_page: String(state.perPage),
  });

  if (state.search.trim() !== "") {
    params.set("search", state.search.trim());
  }

  //call the API endpoint to get the customer data
  //alert(`${params.toString()}`);
  const response = await fetch(`/api/customers.php?${params.toString()}`);
  const payload = await response.json();

  render(payload.data);
  
  state.totalPages = payload.pagination.total_pages;
  meta.textContent = `Page ${payload.pagination.page} of ${payload.pagination.total_pages} • ${payload.pagination.total} total`;
  previousButton.disabled = payload.pagination.page <= 1;
  nextButton.disabled = payload.pagination.page >= payload.pagination.total_pages;
  status.textContent = payload.pagination.total === 0 ? "No customers found." : "Loaded successfully.";
}

//this will do the runtime creation of div 
function render(customers) {
  list.innerHTML = "";

  if (customers.length === 0) {
    list.innerHTML = '<article class="card"><h2>No results</h2><p>Try a broader search term.</p></article>';
    return;
  }

  for (const customer of customers) {
    const article = document.createElement("article");
    article.className = "card";
    article.setAttribute("role", "listitem");

    const website = customer.website
      ? `<p><a href="${customer.website}" target="_blank" rel="noreferrer">Visit website</a></p>`
      : "";
    const title = customer.title ? `<p>${escapeHtml(customer.title)}</p>` : "";
    const company = customer.company ? `<p>${escapeHtml(customer.company)}</p>` : "";
    const city = customer.city ? `<p>${escapeHtml(customer.city)}</p>` : "";

    article.innerHTML = `
      <h2>${escapeHtml(customer.first_name)} ${escapeHtml(customer.last_name)}</h2>
      <p>${escapeHtml(customer.email)}</p>
      ${title}
      ${company}
      ${city}
      ${website}
    `;

    list.appendChild(article);
  }
}

//converts dangerous HTML characters into safe text so the browser displays them instead of interpreting them as HTML or JavaScript.
function escapeHtml(value) {
  return String(value)
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#39;");
}

searchInput.addEventListener("input", (event) => {
  clearTimeout(searchTimer);
  searchTimer = window.setTimeout(() => {
    state.search = event.target.value;
    state.page = 1;
    void loadCustomers();
  }, 250);  //have to wait 250ms / 0.25 seconds after user stops typing before sending the request 1000ms = 1 second
});

perPageSelect.addEventListener("change", (event) => {
  //alert('here per page check');
  state.perPage = Number(event.target.value);
  state.page = 1;
  void loadCustomers();
});

previousButton.addEventListener("click", () => {
  //alert('check the previous button');
  if (state.page > 1) {
    state.page -= 1; //minus 1 on the page value
    void loadCustomers();
  }
});

nextButton.addEventListener("click", () => {
  //alert('check the next button');
  if (state.page < state.totalPages) {
    state.page += 1;
    void loadCustomers();
  }
});

//default loading of customer
void loadCustomers();
