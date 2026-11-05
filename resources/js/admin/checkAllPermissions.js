document.addEventListener("DOMContentLoaded", function() {
    const dataInputs = document.querySelector("#data_inputs");
    const infoInputs = document.querySelector("#info_inputs");

    if (!dataInputs || !infoInputs) {
        return;
    }

    const dataCheckboxes = dataInputs.querySelectorAll("input[type=checkbox]");
    const infoCheckboxes = infoInputs.querySelectorAll("input[type=checkbox]");
    const checkAllData = dataInputs.querySelector("#check_all_data");
    const checkAllInfo = infoInputs.querySelector("#check_all_info");

    var notCheckedData = 0;
    dataCheckboxes.forEach((element) => {
        if (!element.checked) {
            notCheckedData++;
        }
    });
    if (checkAllData) {
        checkAllData.checked = notCheckedData <= 1;
        checkAllData.addEventListener("change", (e) => {
            dataCheckboxes.forEach((element) => {
                element.checked = e.target.checked;
            });
        });
    }

    var notCheckedInfo = 0;
    infoCheckboxes.forEach((element) => {
        if (!element.checked) {
            notCheckedInfo++;
        }
    });
    if (checkAllInfo) {
        checkAllInfo.checked = notCheckedInfo <= 1;
        checkAllInfo.addEventListener("change", (e) => {
            infoCheckboxes.forEach((element) => {
                element.checked = e.target.checked;
            });
        });
    }
});
