//? Number Validation :--
let User_Number = document.querySelectorAll(".User_Number");

User_Number.forEach((number_field) => {
  number_field.addEventListener("input", () => {
    if (number_field.validity.patternMismatch) {
      number_field.setCustomValidity(
        "It seems the number is invalid, Your number must start with 9, 8, 7 or 6 and it must be 10 digits only."
      );
    } else {
      number_field.setCustomValidity("");
    }
  });
});

// === POPUP Calendly ====

// === POPUP Calendly ===
const popupOpenBtns = document.querySelectorAll(".openModal");
const calendlyPopup = document.querySelector(".popUpCalendly");
const calendlyCloseBtn = document.querySelector(".close_calendly");

const submitBtn = document.getElementById("submit_btn");

popupOpenBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    calendlyPopup.style.display = "flex";
  });
});

calendlyCloseBtn.addEventListener("click", () => {
  calendlyPopup.style.display = "none";
});

document.addEventListener("DOMContentLoaded", function () {
  // DOM Elements
  const calendarDays = document.getElementById("popup_cal_calendar-days");
  const currentMonthDisplay = document.getElementById(
    "popup_cal_current-month"
  );
  const prevMonthBtn = document.getElementById("popup_cal_prev-month");
  const nextMonthBtn = document.getElementById("popup_cal_next-month");
  const selectedDateDisplay = document.querySelectorAll(
    ".popup_cal_selected-date"
  );
  const timeSlotContainer = document.querySelector(
    ".popup_cal_time-slots-section"
  );
  const timeDisplay = document.querySelector(".time_display");
  const calenderArea = document.querySelector(".popup_cal_right-panel");
  const nextBtns = document.querySelectorAll(".popup_cal_next-button");
  const modalForm = document.querySelector(".popupForm");
  const previousBtn = document.querySelector(".previous_btn_popup");
  const addGuestBtn = document.querySelector(".add_guest");
  const addGuestInput = document.getElementById("add_guestInput");
  const dateInput = document.querySelector(".selected_date_Input");
  const timeInput = document.querySelector(".selected_time_Input");
  const timeInputUser = document.querySelector(".selected_time_Input_user");
  const noTimeSlotDiv = document.querySelector(".no_slots_div");
  const timeZoneSelected = document.getElementById("timezoneSelect");

  let selectedDate = new Date();

  // ====== Display Current Date ======
  const currentDate = new Date();
  const options = {
    weekday: "long",
    month: "short",
    day: "numeric",
    year: "numeric",
  };

  // dateInput.value = currentDate;

  // ====== Timezone Handling ======
  function adjustTime(
    originalHours,
    originalMinutes,
    offsetHours,
    offsetMinutes
  ) {
    let adjustedHours = originalHours + offsetHours;
    let adjustedMinutes = originalMinutes + offsetMinutes;

    console.log(offsetHours, offsetMinutes);
    if (adjustedMinutes >= 60) {
      adjustedHours += Math.floor(adjustedMinutes / 60);
      adjustedMinutes %= 60;
    } else if (adjustedMinutes < 0) {
      const borrow = Math.ceil(-adjustedMinutes / 60);
      adjustedHours -= borrow;
      adjustedMinutes += borrow * 60;
    }

    if (adjustedHours >= 24) {
      adjustedHours %= 24;
    } else if (adjustedHours < 0) {
      adjustedHours += 24;
    }

    return { hours: adjustedHours, minutes: adjustedMinutes };
  }

  // ====== Dynamic Time Slots Generation ======
  function generateDynamicTimeSlots(selectedDate, ist) {
    const now = new Date();
    const selected = new Date(selectedDate);
    const endTime = new Date(selected);
    endTime.setHours(19, 0, 0, 0);
    let currentTime = new Date(now);

    let timezoneOffset = ist == "true" ? "0" : timeZoneSelected.value;

    const [offsetHoursStr, offsetMinutesStr] = timezoneOffset.split(":");
    const offsetHours = parseInt(offsetHoursStr, 10);
    const offsetMinutes = parseInt(offsetMinutesStr || "0", 10);

    timeSlotContainer.innerHTML = "";

    dateInput.value = selectedDate.toLocaleDateString("en-US", {
      weekday: "long",
      month: "long",
      day: "numeric",
    });

    // console.log(dateInput.value);

    const isToday =
      selected.getDate() === now.getDate() &&
      selected.getMonth() === now.getMonth() &&
      selected.getFullYear() === now.getFullYear();

    if (isToday && currentTime >= endTime) {
      noTimeSlotDiv.style.display = "flex";
      timeSlotContainer.appendChild(noTimeSlotDiv);
      return;
    } else {
      noTimeSlotDiv.style.display = "none";
    }

    let startTime;
    if (isToday) {
      startTime = new Date(now);
      startTime.setMinutes(Math.ceil(startTime.getMinutes() / 30) * 30, 0, 0);
      const elevenAM = new Date(now);
      elevenAM.setHours(11, 0, 0, 0);
      if (startTime < elevenAM) startTime = new Date(elevenAM);
      if (startTime > endTime) startTime = new Date(endTime);
    } else {
      startTime = new Date(selected);
      startTime.setHours(11, 0, 0, 0);
    }

    while (startTime <= endTime) {
      const originalHours = startTime.getHours();
      const originalMinutes = startTime.getMinutes();

      const adjusted = adjustTime(
        originalHours,
        originalMinutes,
        offsetHours,
        offsetMinutes
      );
      const ampm = adjusted.hours >= 12 ? "PM" : "AM";
      let twelveHour = adjusted.hours % 12;
      twelveHour = twelveHour === 0 ? 12 : twelveHour;

      // Add leading zero for single-digit hours
      const paddedHour = twelveHour.toString().padStart(2, "0");
      const timeString = `${paddedHour}:${adjusted.minutes
        .toString()
        .padStart(2, "0")}${ampm}`;

      const buttonGroup = document.createElement("div");
      buttonGroup.classList.add("popup_cal_button-group");

      const originalISTTime = new Date(startTime); // Store original IST time

      const timeSlotSelected = document.createElement("div");
      timeSlotSelected.dataset.istTime = originalISTTime.toISOString(); // Add this line
      timeSlotSelected.classList.add(
        "popup_cal_time-slot-selected",
        "right_form_time_slot"
      );
      timeSlotSelected.textContent = timeString;

      const nextButton = document.createElement("button");
      nextButton.classList.add("popup_cal_next-button");
      nextButton.textContent = "Next";

      buttonGroup.appendChild(timeSlotSelected);
      buttonGroup.appendChild(nextButton);
      timeSlotContainer.appendChild(buttonGroup);

      startTime.setMinutes(startTime.getMinutes() + 30);
    }

    document
      .querySelectorAll(".popup_cal_time-slot-selected")
      .forEach((btn) => {
        btn.addEventListener("click", function () {
          document
            .querySelectorAll(".popup_cal_time-slot-selected")
            .forEach((b) => b.classList.remove("active"));
          this.classList.add("active");
          timeDisplay.textContent = this.textContent;
          timeInputUser.value = this.textContent;
          // console.log(timeInputUser.value);

          const istTime = new Date(this.dataset.istTime);
          const hours = istTime.getHours();
          const minutes = istTime.getMinutes().toString().padStart(2, "0");
          const ampm = hours >= 12 ? "PM" : "AM";
          const twelveHour = hours % 12 || 12;
          const istTimeString = `${twelveHour
            .toString()
            .padStart(2, "0")}:${minutes}${ampm}`;

          // Update displays with IST time
          // timeDisplay.textContent = istTimeString;
          timeInput.value = istTimeString;

          // console.log(timeInput.value);
          // timeInput.value = this.textContent;
        });
      });

    document.querySelectorAll(".popup_cal_next-button").forEach((btn) => {
      btn.addEventListener("click", () => {
        calenderArea.style.display = "none";
        modalForm.classList.add("active");
        previousBtn.style.display = "flex";
      });
    });
  }

  // === submit btn functionality ===
  // submitBtn.addEventListener("click", () => {
  //   generateDynamicTimeSlots(selectedDate, "true");
  //   console.log(timeInput.value);
  // });

  // ====== Timezone Change Handler ======
  timeZoneSelected.addEventListener("change", () => {
    generateDynamicTimeSlots(selectedDate);
    document.querySelector(".time_zone").textContent =
      timeZoneSelected.selectedOptions[0].text;

    document.querySelector(".selected_time_zone").value =
      timeZoneSelected.selectedOptions[0].text.split("(")[0];
    // console.log(document.querySelector(".selected_time_zone").value);
  });

  // ====== Calendar Rendering ======
  const months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];
  let currentMonth = new Date().getMonth();
  let currentYear = new Date().getFullYear();

  function renderCalendar() {
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    calendarDays.innerHTML = "";
    currentMonthDisplay.textContent = `${months[currentMonth]} ${currentYear}`;

    // Disable previous month button if it's the current month
    const currentMonthCheck = new Date();
    if (
      currentMonth === currentMonthCheck.getMonth() &&
      currentYear === currentMonthCheck.getFullYear()
    ) {
      prevMonthBtn.disabled = true;
      prevMonthBtn.classList.add("popup_cal_disabled");
    } else {
      prevMonthBtn.disabled = false;
      prevMonthBtn.classList.remove("popup_cal_disabled");
    }

    for (let i = 0; i < (firstDay + 6) % 7; i++) {
      const day = document.createElement("div");
      day.classList.add("popup_cal_day", "popup_cal_disabled");
      calendarDays.appendChild(day);
    }

    for (let day = 1; day <= daysInMonth; day++) {
      const dayElement = document.createElement("div");
      dayElement.classList.add("popup_cal_day");
      dayElement.textContent = day;

      const date = new Date(currentYear, currentMonth, day);
      const isPast = date < new Date().setHours(0, 0, 0, 0);
      const isSunday = date.getDay() === 0;

      if (isPast || isSunday) {
        dayElement.classList.add("popup_cal_disabled");
        if (isSunday) dayElement.classList.add("popup_cal_sunday");
      } else {
        dayElement.addEventListener("click", () => {
          document
            .querySelectorAll(".popup_cal_day")
            .forEach((d) => d.classList.remove("popup_cal_selected"));
          dayElement.classList.add("popup_cal_selected");
          selectedDate = new Date(currentYear, currentMonth, day);
          selectedDateDisplay.forEach((display) => {
            display.textContent = selectedDate.toLocaleDateString("en-IN", {
              weekday: "long",
              month: "short",
              day: "numeric",
              year: "numeric",
            });
          });
          generateDynamicTimeSlots(selectedDate);
        });
      }

      if (
        day === new Date().getDate() &&
        currentMonth === new Date().getMonth() &&
        currentYear === new Date().getFullYear() &&
        !isSunday
      ) {
        dayElement.classList.add("popup_cal_today", "popup_cal_selected");
        selectedDate = new Date();
        generateDynamicTimeSlots(selectedDate);
      }

      calendarDays.appendChild(dayElement);
    }
  }

  // Add Guest button functionality
  addGuestBtn.addEventListener("click", () => {
    addGuestBtn.style.display = "none";
    addGuestInput.style.display = "flex";
  });

  selectedDateDisplay.forEach((el) => {
    el.textContent = currentDate.toLocaleDateString("en-IN", options);
    // console.log(currentDate.toLocaleDateString("en-IN", options));
  });

  // Previous button functionality
  previousBtn.addEventListener("click", () => {
    calenderArea.style.display = "flex";
    modalForm.classList.remove("active");
    previousBtn.style.display = "none";

    selectedDateDisplay.forEach((el) => {
      el.textContent = currentDate.toLocaleDateString("en-IN", options);
      // console.log(currentDate.toLocaleDateString("en-IN", options));
    });
  });

  prevMonthBtn.addEventListener("click", () => {
    currentMonth--;
    if (currentMonth < 0) {
      currentMonth = 11;
      currentYear--;
    }
    renderCalendar();
  });

  nextMonthBtn.addEventListener("click", () => {
    currentMonth++;
    if (currentMonth > 11) {
      currentMonth = 0;
      currentYear++;
    }
    renderCalendar();
  });

  renderCalendar();
});

// First, let's handle the Meeting Scheduler class

// Email handling functionality
// Email handling functionality
const forms = [
  document.querySelector(".right_sticky_form"),
  document.querySelector(".popupForm"),
];

forms.forEach((form) => {
  if (!form) return;

  const emailInput = form.querySelector("#email-input");
  const emailContainer = form.querySelector("#email-container");

  if (!emailInput || !emailContainer) return;

  function handleEmailInput() {
    let email = emailInput.value.trim();
    email = email.replace(/[, ]$/, "");

    if (email) {
      if (validateEmail(email)) {
        addEmailChip(email, emailContainer);
        updateEmailInputValue(emailContainer, emailInput);
        emailInput.value = "";
      } else {
        showTooltip(emailInput, "Invalid email format!");
      }
    }
  }

  emailInput.addEventListener("keyup", function (event) {
    if (
      event.key === "Enter" ||
      event.key === "," ||
      event.key === " " ||
      emailInput.value.endsWith(",") ||
      emailInput.value.endsWith(" ")
    ) {
      handleEmailInput();
      event.preventDefault();
    }
  });

  emailInput.addEventListener("blur", handleEmailInput);

  emailContainer.addEventListener("click", function (event) {
    if (event.target.classList.contains("close-btn")) {
      const chip = event.target.parentElement;
      emailContainer.removeChild(chip);
    }
  });

  emailContainer.addEventListener(
    "blur",
    function (event) {
      if (event.target.classList.contains("email-chip")) {
        const email = event.target.textContent.replace("×", "").trim();
        if (validateEmail(email)) {
          event.target.innerHTML = `${email} <span class="close-btn">×</span>`;
          event.target.classList.remove("invalid-email");
        } else {
          emailContainer.removeChild(event.target);
        }
      }
    },
    true
  );

  emailContainer.addEventListener("keydown", function (event) {
    if (
      event.target.classList.contains("email-chip") &&
      event.key === "Enter"
    ) {
      event.target.blur();
      event.preventDefault();
    }
  });

  form.addEventListener("submit", function () {
    updateEmailInputValue(emailContainer, emailInput);
  });
});

function validateEmail(email) {
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailPattern.test(email);
}

function addEmailChip(email, emailContainer) {
  const chip = document.createElement("div");
  chip.classList.add("email-chip");
  chip.setAttribute("contenteditable", "true");
  chip.innerHTML = `
          ${email}
          <span class="close-btn">×</span>
      `;
  emailContainer.prepend(chip);
}

function updateEmailInputValue(emailContainer, emailInput) {
  const chips = emailContainer.querySelectorAll(".email-chip");
  const emailList = Array.from(chips)
    .filter((chip) => validateEmail(chip.textContent.replace("×", "").trim()))
    .map((chip) => chip.textContent.replace("×", "").trim());

  emailInput.value = emailList.length > 0 ? emailList.join(", ") : "";
}

function showTooltip(inputElement, message) {
  const existingTooltip = inputElement.parentElement.querySelector(".tooltip");
  if (existingTooltip) existingTooltip.remove();

  const tooltip = document.createElement("div");
  tooltip.classList.add("tooltip");
  tooltip.textContent = message;

  inputElement.parentElement.appendChild(tooltip);
  tooltip.style.top = `${inputElement.offsetTop + inputElement.offsetHeight}px`;
  tooltip.style.left = `${inputElement.offsetLeft}px`;

  setTimeout(() => {
    if (tooltip.parentElement) tooltip.remove();
  }, 3000);
}
