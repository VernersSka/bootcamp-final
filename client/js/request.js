const request = {
  /**
   * 1) Apstādina submit notikumu
   * 2) Nosūta formas datus uz adresi aprakstī†u atributā "action"
   * 3) Saņemot atbildi no servera, izsauc funkciju callback, ja tāda ir padota
   *
   * @param {*} event
   * @param {*} form
   * @param {function || false} callback() - function parameters (responseText, form)
   */
  post: function (form, callback = false) {
    let url = form.getAttribute('action');
    let data = new FormData(form);

    console.log(url);

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
      if (callback !== false) {
        console.log(this.responseText);
        let response_object = JSON.parse(this.responseText);
        if (response_object.status == true) {
          callback(response_object, form);
        }
      }
    };
    xhttp.open('POST', url);

    xhttp.send(data);
  },

  get: function (url, callback = false) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
      if (callback !== false) {
        let response_object = JSON.parse(this.responseText);
        if (response_object.status == true) {
          callback(response_object);
        }
      }
    };
    xhttp.open('GET', url);
    xhttp.send();
  },
};
