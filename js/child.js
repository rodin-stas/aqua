window.onload = function () {
  const result = document.querySelector(".col.result"),
    resultItem = result.querySelectorAll(".item"),
    oknoPodbora = document.querySelector(".row.row-collapse.podbor"),
    resultClose = document.querySelector(".result-close"),
    goCardBtn = result.querySelector(".add_to_card"),
    tochka = document.querySelector(".tochka"),
    inputs = document.querySelectorAll(".equipment-selection__elem-value");
  console.log(oknoPodbora);
  console.log(result);
  // Радиобаттоны инпуты кнопк
  const waterSource = document.querySelectorAll('input[name="water-source"]'),
    waterCondition = document.querySelectorAll('input[name="water-condition"]'),
    btnResulr = document.querySelector(".button.primary.lowercase.go-result"),
    waterPoinsInput = document.querySelector(
      ".equipment-selection__point-water"
    ),
    sreda = document.querySelector('input[name="pa_sreda"]'), // реакция среды
    mineral = document.querySelector('input[name="pa_minral"]'), // Минерализация
    marg = document.querySelector('input[name="pa_marg"]'), // Марганец
    ftor = document.querySelector('input[name="pa_ftorid"]'), // Фториды В подборе умягчителя не влияют. Для мариона нудны от 1.5 до 5
    ferrum = document.querySelector('input[name="pa_ferrum"]'), // Железо
    okis = document.querySelector('input[name="pa_okis"]'), // Окисляемость В подборе умягчителя не влияют. Для уф лапмы нудны(от 4 точно нужо)
    sera = document.querySelector('input[name="pa_serov"]'), // Сероврдород  Это запах перенести в прво в чекбоксы
    dop = document.querySelector('input[name="pa_dop"]'), // Жесткость
    sulfid = document.querySelector('input[name="pa_sulfid"]'), // Сульфиды
    nit = document.querySelector('input[name="pa_nit"]'); // Нитраты

  let resultIdsurbidity = [],
    resultIdWaterPoint = [],
    resultSreda = [],
    resultMineral = [],
    resultMarg = [],
    resultFtor = [],
    resultFerrum = [],
    resultOkis = [],
    resultSera = [],
    resultDop = [],
    resultSulfid = [],
    resultNit = [],
    resultSeraSul = [],
    goCard = [];

  // Функция сотрировки готового массива умягчителей

  function addId() {
    const itmId = result.querySelectorAll(".card__id");
    goCard = [];
    itmId.forEach((item) => {
      goCard.push(item.outerText);
    });

    if (goCard.length > 0) {
      goCard.forEach((item) => {
        const xhr = new XMLHttpRequest();
        console.log(item);
        xhr.addEventListener("load", () => {
          console.log("Товар добавлен");
          window.location.href = "cart";
        });
        // 1. Конфигурируем его: POST-запрос на URL '?add-to-cart'
        xhr.open("POST", `?add-to-cart=${item}`, false); // false - cинхронный запрос
        // 2. Отсылаем запрос
        xhr.send(xhr);
      });
    }
    // if (ccard.length > 0) {
    //   const xhr = new XMLHttpRequest();
    //   // 1. Конфигурируем его: POST-запрос на URL '?add-to-cart'
    //   xhr.open("POST", `?add-to-cart=${ccard}`, false); // false - cинхронный запрос
    //   // 2. Отсылаем запрос
    //   xhr.send();

    //   // window.location.href = "cart";
    // }
    console.log(goCard);
    // console.log(itmId);
  }
  function sort(mas) {
    mas.sort((prev, next) => prev.product.price - next.product.price);
    console.log(mas);
    return mas;
  }

  // Функция ренедера Гросс20
  function renderGross(x) {
    resultItem[x].innerHTML = "";
    resultItem[x].insertAdjacentHTML(
      "afterbegin",
      `<a href="${gross[0].product.slug}" class="card">
        <img src="${gross[0].urlPic}" class="card__pic">
        <div class="wrapper-info"
        <h4 class=card__title>${gross[0].product.name}</h4>
        <p class="card__price">${gross[0].product.price} руб.</p>
        <p class="card__id">${gross[0].product.id}</p>
        </div>
        </a>`
    );
  }
  // Функция ренедера Фильтров Гросс20
  function renderFilterGross() {
    waterSource.forEach((item) => {
      if (item.checked && item.value == "water-pipes") {
        resultItem[1].innerHTML = "";
        resultItem[1].insertAdjacentHTML(
          "afterbegin",
          `<a href="${filtersWaterPipes[0].product.slug}" class="card">
          <img src="${filtersWaterPipes[0].urlPic}" class="card__pic">
          <div class="wrapper-info2"
          <h4 class=card__title>${filtersWaterPipes[0].product.name}</h4>
          <p class="card__price">${filtersWaterPipes[0].product.price} руб.</p>
          <p class="card__id">${filtersWaterPipes[0].product.id}</p>
          </div>
          </a>`
        );
      } else if (
        item.checked &&
        (item.value == "well" || item.value == "well-2")
      ) {
        resultItem[1].innerHTML = "";
        resultItem[1].insertAdjacentHTML(
          "afterbegin",
          `<a href="${filtersWaterPipes[1].product.slug}" class="card">
          <img src="${filtersWaterPipes[1].urlPic}" class="card__pic">
          <div class="wrapper-info2"
          <h4 class=card__title>${filtersWaterPipes[1].product.name}</h4>
          <p class="card__price">${filtersWaterPipes[1].product.price} руб.</p>
          <p class="card__id">${filtersWaterPipes[1].product.id}</p>
          </div>
          </a>`
        );
      }
    });
  }
  // Функция ренедера Фильтров Гросс20 постфтлтт по умолчанию
  function renderPostFilterGross() {
    resultItem[5].innerHTML = "";
    resultItem[5].insertAdjacentHTML(
      "afterbegin",
      `<a href="${filtersWaterPipes[0].product.slug}" class="card">
    <img src="${filtersWaterPipes[0].urlPic}" class="card__pic">
    <div class="wrapper-info2"
    <h4 class=card__title>${filtersWaterPipes[0].product.name}</h4>
    <p class="card__price">${filtersWaterPipes[0].product.price} руб.</p>
    <p class="card__id">${filtersWaterPipes[0].product.id}</p>
    </div>
    </a>`
    );
  }

  // Функция ренедера Уф лампы
  function renderUflamp() {
    resultItem[6].innerHTML = "";
    resultItem[6].insertAdjacentHTML(
      "afterbegin",
      `<a href="${uf[0].product.slug}" class="card">
        <img src="${uf[0].urlPic}" class="card__pic">
        <div class="wrapper-info"
        <h4 class=card__title>${uf[0].product.name}</h4>
        <p class="card__price">${uf[0].product.price} руб.</p>
        <p class="card__id">${uf[0].product.id}</p>
        </div>
        </a>`
    );
  }
  // Функция ренедера мариона
  function renderMarion() {
    resultItem[7].innerHTML = "";
    resultItem[7].insertAdjacentHTML(
      "afterbegin",
      `<a href="${marion[0].product.slug}" class="card">
        <img src="${marion[0].urlPic}" class="card__pic">
        <div class="wrapper-info2"
        <h4 class=card__title>${marion[0].product.name}</h4>
        <p class="card__price">${marion[0].product.price} руб.</p>
        <p class="card__id">${marion[0].product.id}</p>
        </div>
        </a>`
    );
  }

  // Функция ренедера вотербоса
  function renderWb(mas) {
    if (mas.length > 0) {
      resultItem[2].innerHTML = "";
      resultItem[2].insertAdjacentHTML(
        "afterbegin",
        `<a href="${mas[0].product.slug}" class="card">
            <img src="${mas[0].urlPic}" class="card__pic">
            <div class="wrapper-info"
            <h4 class=card__title>${mas[0].product.name}</h4>
            <p class="card__price">${mas[0].product.price} руб.</p>
            <p class="card__id">${mas[0].product.id}</p>
            </div>
            </a>`
      );
    } else {
      renderWb0();
    }
  }

  // Функция ренедера соли
  function renderSolt() {
    resultItem[3].innerHTML = "";
    resultItem[3].insertAdjacentHTML(
      "afterbegin",
      `<a href="${solt[0].product.slug}" class="card">
          <img src="${solt[0].urlPic}" class="card__pic">
          <div class="wrapper-info2"
          <h4 class=card__title>${solt[0].product.name}</h4>
          <p class="card__price">${solt[0].product.price} руб.</p>
          <p class="card__id">${solt[0].product.id}</p>
          </div>
          </a>`
    );
  }

  // Функция ренедера вотербоса Если не подобрали
  function renderWb0() {
    resultItem[2].innerHTML = "";

    resultItem[2].insertAdjacentHTML(
      "afterbegin",
      `<p  class="card">
        <span>По вашим данным не удалось подобрать умягчиель. Позвоните Дену. </span>
        </p>`
    );
  }
  // Функция ренедера постфильтра по умолчагию
  function renderPostFiltrs(mas) {
    resultItem[4].innerHTML = "";
    resultItem[5].innerHTML = "";
    // console.log(mas[0].product.name);
    if (
      mas[0].product.name == "Aquaphor ProPlus 380P" ||
      mas[0].product.name == "Aquaphor ProPlus 380"
    ) {
      resultItem[4].insertAdjacentHTML(
        "afterbegin",
        `<a href="${viking[0].product.slug}" class="card">
          <img src="${viking[0].urlPic}" class="card__pic">
          <div class="wrapper-info"
          <h4 class=card__title>${viking[0].product.name}</h4>
          <p class="card__price">${viking[0].product.price} руб.</p>
          <p class="card__id">${viking[0].product.id}</p>
          </div>
          </a>`
      );

      resultItem[5].insertAdjacentHTML(
        "afterbegin",
        `<a href="${filtersWaterPipes[2].product.slug}" class="card">
        <img src="${filtersWaterPipes[2].urlPic}" class="card__pic">
        <div class="wrapper-info2"
        <h4 class=card__title>${filtersWaterPipes[2].product.name}</h4>
        <p class="card__price">${filtersWaterPipes[2].product.price} руб.</p>
        <p class="card__id">${filtersWaterPipes[2].product.id}</p>
        </div>
        </a>`
      );
    } else {
      renderGross(4);
      renderPostFilterGross();
    }
  }

  // Функия выборки по мутности воды возвращает массив Умягчителей
  function surbidity() {
    resultIdsurbidity = [];
    waterCondition.forEach((item) => {
      if (item.checked && item.value == "water-pipes") {
        console.log("Выбрна прозрачная вода");
        systems.forEach((system) => {
          system.attributes.forEach((attr) => {
            if (attr[1] == "pa_clear_turbidity" && attr[2] == "нет") {
              resultIdsurbidity.push(system);
            }
          });
        });
        console.log(
          `Воду фильтровать не нужно! Подходит ${resultIdsurbidity.length} Умягчителей`
        );
        console.log(resultIdsurbidity);
      } else if (item.checked && item.value == "muddy") {
        console.log("Выбрана мутная вода");
        systems.forEach((system) => {
          system.attributes.forEach((attr) => {
            if (attr[1] == "pa_clear_turbidity" && attr[2] == "да") {
              resultIdsurbidity.push(system);
            }
          });
        });
        console.log(
          `Воду НУЖНО фильровать! Подходит ${resultIdsurbidity.length} Умягчителей`
        );
      }
    });
    return resultIdsurbidity;
  }

  // Функия выборки по точкам водозбора возвращает массив Умягчителей
  function waterPoint() {
    resultIdWaterPoint = [];
    resultIdsurbidity.forEach((system) => {
      system.attributes.forEach((attr) => {
        attr.forEach((att, i) => {
          if (
            att == "pa_water_points" &&
            waterPoinsInput.value > attr[i + 1] &&
            waterPoinsInput.value <= attr[i + 2]
          ) {
            resultIdWaterPoint.push(system);
          }
        });
      });
    });
    console.log(
      `Выбрано ${waterPoinsInput.value} точек возбора. Под это подходит ${resultIdWaterPoint.length} умягчителя/ей`
    );
    console.log(resultIdWaterPoint);
    return resultIdWaterPoint;
  }

  // Функия выборки по Реакция среды  возвращает массив Умягчителей
  function sredaSample() {
    resultSreda = [];
    resultIdWaterPoint.forEach((system) => {
      system.attributes.forEach((attr) => {
        attr.forEach((att, i) => {
          if (
            att == "pa_vodorod-p" &&
            attr[i + 1] <= +sreda.value &&
            attr[i + 2] >= +sreda.value
          ) {
            resultSreda.push(system);
          }
        });
      });
    });
    console.log(
      `Водородный показатель = ${sreda.value} (от 6 до 9). Под это подходит ${resultSreda.length} умягчителя/ей`
    );
    console.log(resultSreda);
    return resultSreda;
  }

  // Функия выборки по минерализации возвращает массив Умягчителей
  function mineralSample() {
    resultMineral = [];
    resultSreda.forEach((system) => {
      system.attributes.forEach((attr) => {
        attr.forEach((att, i) => {
          if (
            att == "pa_mineralization" &&
            attr[i + 1] >= +mineral.value &&
            attr[i + 2] <= +mineral.value
          ) {
            resultMineral.push(system);
          }
        });
      });
    });
    console.log(
      `Минерализауия = ${mineral.value} (от 200 до 1000). Под это подходит ${resultMineral.length} умягчителя/ей`
    );
    console.log(resultMineral);
    return resultMineral;
  }

  // Функия выборки по маргоннцу   возвращает массив Умягчителей
  function margSample() {
    resultMarg = [];
    resultMineral.forEach((system) => {
      system.attributes.forEach((attr) => {
        attr.forEach((att, i) => {
          if (
            att == "pa_mn" &&
            attr[i + 1] <= +marg.value &&
            attr[i + 2] >= +marg.value
          ) {
            resultMarg.push(system);
          }
        });
      });
    });
    console.log(
      `Марганец = ${marg.value} (от 0 до 5). Под это подходит ${resultMarg.length} умягчителя/ей`
    );
    console.log(resultMarg);
    return resultMarg;
  }

  // Функия выборки по железу возвращает массив Умягчителей
  function ferrumSample() {
    resultFerrum = [];
    resultMarg.forEach((system) => {
      system.attributes.forEach((attr) => {
        attr.forEach((att, i) => {
          if (
            att == "pa_ferrum" &&
            attr[i + 1] <= +ferrum.value &&
            attr[i + 2] >= +ferrum.value
          ) {
            resultFerrum.push(system);
          }
        });
      });
    });
    console.log(
      `Железо = ${ferrum.value} (<=3 или <=10). Под это подходит ${resultFerrum.length} умягчителя/ей`
    );
    console.log(resultFerrum);
    return resultFerrum;
  }
  // Функия выборки по нитратам возвращает массив Умягчителей
  function nitSample() {
    resultNit = [];
    resultFerrum.forEach((system) => {
      system.attributes.forEach((attr) => {
        attr.forEach((att, i) => {
          if (
            att == "pa_nitrate" &&
            attr[i + 1] <= +nit.value &&
            attr[i + 2] >= +nit.value
          ) {
            resultNit.push(system);
          }
        });
      });
    });
    console.log(
      `Нитраты= ${sulfid.value} (0.003). Под это подходит ${resultNit.length} умягчителя/ей`
    );
    console.log(resultNit);
    return resultNit;
  }

  // Функия выборки по компенсированной жесткости возвращает массив Умягчителей
  function kompSample() {
    let a, d, res;
    resultDop = [];
    resultNit.forEach((system) => {
      console.log(system.attributes[8][4]); //это мкс значение к жесткости
      console.log(system.attributes[11][2]); // Это множитель д
      a = dop.value * 50;
      d = (+ferrum.value + +marg.value) * system.attributes[11][2];
      res = (a + d) / 50;
      console.log(`А = ${a}`);
      console.log(`D = ${d}`);
      console.log(`Комренировання жесткость = ${res}`);
      if (system.attributes[8][4] >= res) {
        resultDop.push(system);
        console.log(
          `Комренировання жесткость = ${res} для ${system.product.name}`
        );
      }
      console.log(resultDop);
    });
  }

  // Функия выборки по сульфидам возвращает массив Умягчителей
  function sulfidSeraSample(mas, masAut) {
    if (+sulfid.value >= +sera.value) {
      sulfidSample(mas, masAut);
    } else if (+sulfid.value < +sera.value) {
      seraSample(mas, masAut);
    }
    return masAut;
  }

  // Функия выборки по сероводоруду возвращает массив Умягчителей
  function seraSample(mas) {
    resultSera = [];
    mas.forEach((system) => {
      system.attributes.forEach((attr) => {
        attr.forEach((att, i) => {
          if (
            att == "pa_h2s" &&
            attr[i + 1] <= +sera.value &&
            attr[i + 2] >= +sera.value
          ) {
            resultSera.push(system);
          }
        });
      });
    });
    console.log(
      `Серводород= ${sera.value} ( все < 0.003 кпоме 400р и 380р). Под это подходит ${resultSera.length} умягчителя/ей`
    );
    console.log(resultSera);
    return resultSera;
  }

  // Функия выборки по сульфидам возвращает массив Умягчителей
  function sulfidSample(mas) {
    resultSulfid = [];
    mas.forEach((system) => {
      system.attributes.forEach((attr) => {
        attr.forEach((att, i) => {
          if (
            att == "pa_sulphide" &&
            attr[i + 1] <= +sulfid.value &&
            attr[i + 2] >= +sulfid.value
          ) {
            resultSulfid.push(system);
          }
        });
      });
    });
    console.log(
      `Сульфиды= ${sulfid.value} (0.003). Под это подходит ${resultSulfid.length} умягчителя/ей`
    );
    console.log(resultSulfid);
    return resultSulfid;
  }

  // бугунок точек водозбора
  waterPoinsInput.addEventListener("change", () => {
    tochka.textContent = waterPoinsInput.value;
  });
  // Замена , в инпутах
  inputs.forEach((item) => {
    item.addEventListener("change", () => {
      item.value = item.value.replace(",", ".");
      console.log("заменили");
    });
  });

  // Добавить в корзину
  goCardBtn.addEventListener("click", () => {
    addId(goCard);
    console.log("Добавили в корзину");
  });

  // Обработчик Кнопки -ОСНОВНОЙ БЛОК
  resultClose.addEventListener("click", () => {
    resultSera = [];
    resultSeraSul = [];
    oknoPodbora.classList.remove("none");
    result.classList.add("none");
  });
  btnResulr.addEventListener("click", () => {
    result.classList.remove("none");
    oknoPodbora.classList.add("none");
    console.log("Кликнули кнопку 'Рассчитать онлайн'");
    renderGross(0);
    console.log("Вывели Гросс-20");
    renderFilterGross();
    console.log(
      "Вывели фильтр (Если водопровод то-В520 Если скажина/колодец-то ЭФГ)"
    );
    renderUflamp();
    renderMarion();
    surbidity();
    if (resultIdsurbidity.length > 0) {
      waterPoint();
    } else {
      renderWb0();
      renderGross(4);
      renderPostFilterGross();
    }

    if (resultIdWaterPoint.length > 0) {
      sredaSample();
    } else {
      renderWb0();
      renderGross(4);
      renderPostFilterGross();
    }
    if (resultSreda.length > 0) {
      mineralSample();
    } else {
      renderWb0();
      renderGross(4);
      renderPostFilterGross();
    }
    if (resultMineral.length > 0) {
      margSample();
    } else {
      renderWb0();
      renderGross(4);
      renderPostFilterGross();
    }

    if (resultMarg.length > 0) {
      ferrumSample();
    } else {
      renderWb0();
      renderGross(4);
      renderPostFilterGross();
    }
    if (resultFerrum.length > 0) {
      nitSample();
    } else {
      renderWb0();
      renderGross(4);
      renderPostFilterGross();
    }

    if (resultNit.length > 0) {
      kompSample();
    } else {
      renderWb0();
      renderGross(4);
      renderPostFilterGross();
    }
    if (resultDop.length > 0) {
      sulfidSeraSample(resultDop, resultSeraSul);
    } else {
      renderWb0();
      renderGross(4);
      renderPostFilterGross();
    }
    if (resultSera.length >= 1 || resultSulfid.length >= 1) {
      if (resultSera.length >= 1) {
        sort(resultSera);
        renderWb(resultSera);
        renderSolt();
        renderPostFiltrs(resultSera);
        // addId(goCard);
      } else if (resultSulfid.length >= 1) {
        sort(resultSulfid);
        renderWb(resultSulfid);
        renderSolt();
        renderPostFiltrs(resultSulfid);
        // addId(goCard);
      }
    } else {
      renderWb0();
      renderGross(4);
      renderPostFilterGross();
    }
  });
};
