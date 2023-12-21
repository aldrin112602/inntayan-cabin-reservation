!(function e() {
  let n = document.getElementById("searchInput"),
    t = document.getElementById("startButton");
  if ("webkitSpeechRecognition" in window)
    var r = new webkitSpeechRecognition();
  else if (!("SpeechRecognition" in window)) return;
  else var r = new SpeechRecognition();
  (r.continuous = !1),
    (r.interimResults = !1),
    (r.lang = "en-US"),
    (r.onresult = function (e) {
      let t = e.results[0][0].transcript;
      n.value = t;
      w3.filterHTML("#tbl", "tr", t);
    }),
    (r.onerror = function (e) {
      console.error("Speech recognition error:", e.error);
    }),
    t.addEventListener("click", function () {
      r.start();
    });
})();

function updateContent(cell, id, column, tableName) {
  const newValue = cell.innerText.trim(),
    originalValue = cell.getAttribute("data-original-value").trim();
  if (newValue && newValue != originalValue) {
    fetch("./update_script.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `id=${id}&column=${column}&value=${encodeURIComponent(
        newValue
      )}&table=${tableName}`,
    })
      .then((response) => {
        if (response.ok) {
          const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener("mouseenter", Swal.stopTimer);
              toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
          });

          Toast.fire({
            icon: "success",
            title: "Updated successfully",
          });
          cell.setAttribute("data-original-value", newValue);
        } else {
          const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener("mouseenter", Swal.stopTimer);
              toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
          });

          Toast.fire({
            icon: "error",
            title: "Sorry, failed to update content",
          });
        }
      })
      .catch((error) => {
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
          },
        });

        Toast.fire({
          icon: "error",
          title: error,
        });
      });
  }
}

function handleKeyPress(event, cell, id, column, tableName) {
  if (event.key === "Enter") {
    event.preventDefault();
    updateContent(cell, id, column, tableName);
    cell.blur();
  }
}

function showConfirmation(id, type, action) {
  (action.trim() != "update" &&
    Swal.fire({
      title: "Are you sure?",
      text: "This action cannot be undone.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, proceed!",
      cancelButtonText: "Cancel",
      onclose: function () {
        window.open(`./records.php`, "_self");
      },
      reverseButtons: true,
    }).then(
      (result) =>
        result.isConfirmed &&
        window.open(`?id=${id}&type=${type}&action=${action}`, "_self")
    )) ||
    window.open(`?id=${id}&type=${type}&action=${action}`, "_self");
}

$(document).ready(function () {
  $('button[id="action_btn"]').on("click", function () {
    let id = $(this).attr("data-id");
    let type = $(this).attr("data-type");
    let action = $(this).attr("data-action");
    showConfirmation(id, type, action);
  });
  
  $('th[contenteditable="true"]')
    .on("focus", function () {
      $(this).addClass("resize_th form-control");
    })
    .on("blur", function () {
      $(this).removeClass("resize_th form-control");
    });
});


