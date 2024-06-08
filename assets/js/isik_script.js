const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

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

function DataSweetFormInit() {
    $(".datasweetform").on("submit", function (e) {
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

                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Teşekkürler',
                            text: response.response_message,
                            confirmButtonText: 'Tamam'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                //window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata',
                            text: response.response_message,
                            confirmButtonText: 'Tamam'
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        });
                    }
                },
            });
        }
    });
}

DataSweetFormInit();



var main_event_slider = new Swiper(".main-event-slider", {
    slidesPerView: 1,
    spaceBetween: 15,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    breakpoints: {
        640: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
        768: {
            slidesPerView: 2,
            spaceBetween: 30,
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 30,
        },
    },
});

var old_event_slider = new Swiper(".old-event-slider", {
    slidesPerView: 1,
    spaceBetween: 15,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    breakpoints: {
        640: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
        991: {
            slidesPerView: 3,
            spaceBetween: 30,
        },
        1024: {
            slidesPerView: 4,
            spaceBetween: 30,
        },
    },
});

function club_sign_init() {
    $(document).on("click", ".club_sign_btn", function (e) {
        e.preventDefault();
        var club_id = parseInt($(this).attr("data-club-id"));
        Swal.fire({
            title: "Kaydol!",
            text: "Kulübe katılmak istediğinizden emin misiniz?",
            icon: "success",
            confirmButtonText: "Evet",
            showCancelButton: true,
            cancelButtonText: "Hayır",
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: '/club-user-sign.php',
                    dataType: "json",
                    data: {id: club_id},
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Teşekkürler',
                                text: response.response_message,
                                confirmButtonText: 'Tamam'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Uyarı',
                                text: response.response_message,
                                confirmButtonText: 'Tamam'
                            }).then((result) => {
                                if (result.isConfirmed) {

                                }
                            });
                        }
                    }
                });
            }
        });
    });
}

club_sign_init();

//PROFİL RESMİ DÜZENLEME KODU
// Butona tıklayınca silme fonksiyonu
$(document).on("click", ".image-remove-btn", function (e) {
    e.preventDefault();
    // Silinecek resmin veritabanındaki kimliği
    var data_id = $(this).attr("data-id");

    var profile_image = $(this).closest("form").find(".profile-img");

    Swal.fire({
        title: "Resim Sil!",
        text: "Silmek istediğinizden emin misiniz?",
        icon: "success",
        confirmButtonText: "Evet",
        showCancelButton: true,
        cancelButtonText: "Hayır",
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "/club-image-remove.php",
                data: { id: data_id },
                success: function (response) {
                    // Logo yerine varsayılan bir resim koyma
                    profile_image.attr("src", "/assets/images/no-image.jpg");
                },
            });
        }
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
//PROFİL RESMİ DÜZENLEME KODU