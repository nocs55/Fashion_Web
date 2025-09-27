// Banner
let index = 0;
let slides = document.querySelectorAll(".slide");

function showSlide(n) {
  slides.forEach((slide) => slide.classList.remove("active"));
  index = (n + slides.length) % slides.length;
  slides[index].classList.add("active");
}

// Chuyển slide tự động mỗi 3 giây
setInterval(() => showSlide(index + 1), 3000);

function changeSlide(n) {
  showSlide(index + n);
}

//sidebar
document.querySelector(".menu-button").addEventListener("click", function () {
  document.querySelector(".nav ul").classList.toggle("active");
});

//right-menu

document.addEventListener("DOMContentLoaded", function () {
  let items = document.querySelectorAll(".best-seller-item");
  let index2 = 0;

  function showNextItem() {
    items.forEach((item) => {
      item.classList.remove("active");
    });

    items[index2].classList.add("active");
    index2 = (index2 + 1) % items.length;
  }

  setInterval(showNextItem, 3000); // Chuyển đổi sản phẩm mỗi 4 giây
  showNextItem(); // Hiển thị sản phẩm đầu tiên ngay khi tải trang
});

// Xem thêm
document.addEventListener("DOMContentLoaded", function () {
  let showMoreBtn = document.getElementById("showMoreBtn");
  let hideBtn = document.getElementById("hideBtn");

  if (showMoreBtn) {
    showMoreBtn.addEventListener("click", function () {
      document
        .querySelectorAll(".news-item.hidden")
        .forEach((item) => item.classList.remove("hidden"));
      showMoreBtn.style.display = "none";
      hideBtn.style.display = "block";
    });
  }

  if (hideBtn) {
    hideBtn.addEventListener("click", function () {
      document.querySelectorAll(".news-item").forEach((item, index) => {
        if (index >= 4) item.classList.add("hidden");
      });
      hideBtn.style.display = "none";
      showMoreBtn.style.display = "block";
    });
  }
});
