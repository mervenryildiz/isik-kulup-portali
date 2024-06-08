function DataFormInit() {
  $(".dataform").on("submit", function (e) {
    if (e.isDefaultPrevented()) {
    } else {
      var FormSubmitButton = $(this).find(".formsubmitbutton");
      var default_button_value = FormSubmitButton.html();
      $(this).ajaxForm({
        delegation: true,
        beforeSubmit: function () {
          FormSubmitButton.attr("disabled", "disabled");
          FormSubmitButton.html("Lütfen bekleyin...");
        },
        success: function (response) {
          FormSubmitButton.removeAttr("disabled");
          FormSubmitButton.html(default_button_value);
          $("#formresult").html(response);
        },
      });
    }
  });
}

DataFormInit();
// Türkçe karakterleri küçük harfe dönüştüren bir fonksiyon
function toLowerTurkish(str) {
  var letters = {
    İ: "i",
    I: "ı",
    Ş: "ş",
    Ğ: "ğ",
    Ü: "ü",
    Ö: "ö",
    Ç: "ç",
  };
  str = str.replace(/(([İIŞĞÜÇÖ]))/g, function (letter) {
    return letters[letter];
  });
  return str.toLowerCase();
}
// Butona tıklayınca silme fonksiyonu
$(document).on("click", ".image-remove-btn", function (e) {
  e.preventDefault();
  // Silinecek resmin veritabanındaki kimliği
  var data_id = $(this).attr("data-id");

  var profile_image = $(this).closest("form").find(".profile-img");
  // AJAX ile resmi kaldırma isteği gönderme
  $.ajax({
    type: "POST",
    url: "club-image-remove.php",
    data: { id: data_id },
    success: function (response) {
      alert("Resim silindi!");
      // Logo yerine varsayılan bir resim koyma
      profile_image.attr("src", "assets/img/no-image.jpg");
    },
  });
});
// Resme tıklandığında resim seçme
$(document).on("click", ".profile-img", function (e) {
  e.preventDefault();
  var imageBox = $(this).closest("div").find(".profile-img-input");
  imageBox.trigger("click.input");
});

$(document).on("change", "input[type=file].profile-img-input", function (e) {
  e.preventDefault();
  var file = $(this).get(0).files[0];
  var imageBox = $(this).closest("div").find(".profile-img");

  if (file) {
    var reader = new FileReader();

    reader.onload = function () {
      // Dosya okunduğunda
      console.log(reader.result);
      // Okunan dosyayı logo olarak ayarla
      imageBox.attr("src", reader.result);
    };

    reader.readAsDataURL(file);
  }
});

$(document).on("keyup", ".user-search-input", function (e) {
  // Aranan değeri küçük harfe dönüştürme
  var search_value = toLowerTurkish($(this).val());
  // Kullanıcı listesinin bulunduğu alan
  var search_list = $(this).closest("div").find(".users-list-table");
  // Kullanıcıları filtreleme
  $(search_list)
    .find(".form-check")
    .each(function () {
      var form_check = $(this);

      var item_name = $(this).attr("data-name");
      var item_email = $(this).attr("data-email");

      var name_index = item_name.indexOf(search_value);
      var email_index = item_email.indexOf(search_value);
      // Aranan değer ile eşleşen kullanıcıları gösterme veya gizleme
      if (name_index !== -1 || email_index !== -1) {
        form_check.show();
      } else {
        form_check.hide();
      }
    });
  console.log(search_value);
});
