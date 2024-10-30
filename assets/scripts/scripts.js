//jQuery
const $ = jQuery;

// OVERLAY
if ($(".main_overlay").hasClass("visible")) {
  $("body").css("overflow", "hidden");
} else {
  $("body").css("overflow", "");
}

// BURGER MENU
$(".header_menu_open").on("click", () => {
  $(".menu").addClass("-open");
  $("body").css("overflow", "hidden");
});

$(".header_menu_close").on("click", () => {
  $(".menu").removeClass("-open");
  if ($(".main_overlay").hasClass("visible")) {
    $("body").css("overflow", "hidden");
  } else {
    $("body").css("overflow", "");
  }
});