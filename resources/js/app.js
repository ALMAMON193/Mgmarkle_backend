import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// ====== Sidebar & Dropdown ======
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("sidebarOverlay");
const mobileButton = document.getElementById("mobileSidebarButton");
const toggleSidebarButton = document.getElementById("toggleSidebarButton");
const sidebarIcon = document.getElementById("sidebarIcon");
const sidebarTitle = document.getElementById("sidebarTitle");
const profileBtn = document.getElementById("profileButton");
const dropdown = document.getElementById("profileDropdown");

// ===== Toggle sidebar mobile =====
if (mobileButton && sidebar && overlay) {
  mobileButton.addEventListener("click", () => {
    sidebar.classList.toggle("-translate-x-full");
    overlay.classList.toggle("hidden");

    if (!sidebar.classList.contains("-translate-x-full")) {
      sidebar.dataset.collapsed = "false";
      sidebarIcon?.classList.add("hidden");
      sidebarTitle?.classList.remove("hidden");
      document.querySelectorAll(".sidebar-text").forEach(t => t.classList.remove("hidden"));
      document.querySelectorAll(".dropdown-icon").forEach(i => i.classList.remove("hidden"));
      document.querySelectorAll(".dropdown-menu").forEach(m => m.classList.add("hidden"));
    }
  });

  overlay.addEventListener("click", () => {
    sidebar.classList.add("-translate-x-full");
    overlay.classList.add("hidden");
  });
}

// ===== Toggle sidebar desktop =====
if (toggleSidebarButton && sidebar) {
  toggleSidebarButton.addEventListener("click", () => {
    const isCollapsed = sidebar.dataset.collapsed === "true";
    sidebar.dataset.collapsed = !isCollapsed;
    sidebar.classList.toggle("w-16", !isCollapsed);
    sidebar.classList.toggle("w-64", isCollapsed);
    sidebarIcon?.classList.toggle("hidden", isCollapsed);
    sidebarTitle?.classList.toggle("hidden", !isCollapsed);

    document.querySelectorAll(".sidebar-text").forEach(t => t.classList.toggle("hidden", !isCollapsed));
    document.querySelectorAll(".dropdown-icon").forEach(i => i.classList.toggle("hidden", !isCollapsed));
    document.querySelectorAll(".dropdown-menu").forEach(m => m.classList.add("hidden"));

    const sidebarLinks = document.querySelectorAll(".sidebar_links");
    if (!isCollapsed) {
      sidebarLinks.forEach(link => link.classList.remove("!px-3"));
      sidebarLinks.forEach(link => link.classList.add("!px-1"));
    }
  });
}

// ===== Dropdown function =====
function toggleDropdown(el) {
  if (!sidebar || sidebar.dataset.collapsed === "true") return;
  const dropdown = el.nextElementSibling;
  if (dropdown) {
    dropdown.classList.toggle("hidden");
    const icon = el.querySelector(".dropdown-icon");
    if (icon) icon.classList.toggle("rotate-90");
  }
}
window.toggleDropdown = toggleDropdown;

// ===== Profile dropdown =====
if (profileBtn && dropdown) {
  profileBtn.addEventListener("click", () => {
    dropdown.classList.toggle("hidden");
    if (!dropdown.classList.contains("hidden")) {
      dropdown.classList.remove("opacity-0", "scale-95");
      dropdown.classList.add("opacity-100", "scale-100");
    } else {
      dropdown.classList.remove("opacity-100", "scale-100");
      dropdown.classList.add("opacity-0", "scale-95");
    }
  });

  document.addEventListener("click", (e) => {
    if (!profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.add("hidden", "opacity-0", "scale-95");
    }
  });
}

// ===== Filter panel =====
const filterButton = document.getElementById("filterButton");
const filterPanel = document.getElementById("filterPanel");
if (filterButton && filterPanel) {
  filterButton.addEventListener("click", () => filterPanel.classList.toggle("hidden"));
}

// ===== Table functionality =====
const tableBody = document.getElementById("tableBody");
const searchInput = document.getElementById("searchInput");
const roleFilter = document.getElementById("roleFilter");
const statusFilter = document.getElementById("statusFilter");
const dateFilter = document.getElementById("dateFilter");
const applyFilters = document.getElementById("applyFilters");
const clearFilters = document.getElementById("clearFilters");
const prevPage = document.getElementById("prevPage");
const nextPage = document.getElementById("nextPage");
const paginationInfo = document.getElementById("paginationInfo");
const totalEntries = document.getElementById("totalEntries");

let currentPage = 1;
const rowsPerPage = 5;
let tableData = tableBody ? Array.from(tableBody.children) : [];
let sortDirection = {};

// ===== Table filtering =====
function applyTableFilters() {
  if (!tableBody) return;

  const searchTerm = searchInput?.value.toLowerCase() || "";
  const selectedRole = roleFilter?.value || "";
  const selectedStatus = statusFilter?.value || "";
  const selectedDate = dateFilter?.value || "";

  let filteredData = tableData.filter(row => {
    const name = row.children[1]?.textContent.toLowerCase() || "";
    const email = row.children[2]?.textContent.toLowerCase() || "";
    const role = row.dataset.role || "";
    const status = row.dataset.status || "";
    const date = row.dataset.date || "";

    return (
      (name.includes(searchTerm) || email.includes(searchTerm)) &&
      (selectedRole === "" || role === selectedRole) &&
      (selectedStatus === "" || status === selectedStatus) &&
      (selectedDate === "" || date === selectedDate)
    );
  });

  const start = (currentPage - 1) * rowsPerPage;
  const end = start + rowsPerPage;
  const paginatedData = filteredData.slice(start, end);

  tableBody.innerHTML = "";
  paginatedData.forEach(row => tableBody.appendChild(row));

  const totalFiltered = filteredData.length;
  if (paginationInfo) paginationInfo.textContent = `${start + 1} to ${Math.min(end, totalFiltered)}`;
  if (totalEntries) totalEntries.textContent = totalFiltered;

  if (prevPage) prevPage.disabled = currentPage === 1;
  if (nextPage) nextPage.disabled = end >= totalFiltered;
}

// ===== Table search & filter events =====
searchInput?.addEventListener("input", () => { currentPage = 1; applyTableFilters(); });
applyFilters?.addEventListener("click", () => { currentPage = 1; applyTableFilters(); });
clearFilters?.addEventListener("click", () => {
  if (roleFilter) roleFilter.value = "";
  if (statusFilter) statusFilter.value = "";
  if (dateFilter) dateFilter.value = "";
  if (searchInput) searchInput.value = "";
  currentPage = 1;
  applyTableFilters();
});

prevPage?.addEventListener("click", () => { if (currentPage > 1) { currentPage--; applyTableFilters(); } });
nextPage?.addEventListener("click", () => {
  const totalPages = Math.ceil(tableData.length / rowsPerPage);
  if (currentPage < totalPages) { currentPage++; applyTableFilters(); }
});

// ===== Table sorting =====
function sortTable(columnIndex) {
  const key = columnIndex === 0 ? "id" : "name";
  sortDirection[key] = !sortDirection[key] ? "asc" : sortDirection[key] === "asc" ? "desc" : "asc";

  tableData.sort((a, b) => {
    let valueA = a.children[columnIndex]?.textContent || "";
    let valueB = b.children[columnIndex]?.textContent || "";

    if (columnIndex === 0) {
      valueA = parseInt(valueA);
      valueB = parseInt(valueB);
    } else {
      valueA = valueA.toLowerCase();
      valueB = valueB.toLowerCase();
    }

    if (sortDirection[key] === "asc") return valueA > valueB ? 1 : -1;
    return valueA < valueB ? 1 : -1;
  });

  applyTableFilters();
}

// ===== Initialize table =====
applyTableFilters();
window.sortTable = sortTable;
