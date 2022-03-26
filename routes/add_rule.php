<?php
session_start();

if (!(isset($_SESSION['logged']) && isset($_SESSION['isAdmin']))) {
    // IF USERS IS NOT LOGGED IN OR
    // IS LOGGED IN AND IS NOT ADMIN,
    // EXIT
    echo "<script>window.location = '" . str_replace("\n", "", $baseURL) . "'</script>";
    exit();
}

$title = "Προσθήκη Κανόνα";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/add_form.css">
    ';
include '../header.php';
?>

<body>
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">
        <div class="form-container">
            <form>
                <div class="form-section">
                    <label for="question-text">Όνομα Κανόνα</label>
                    <input type="text" name="question-text" id="question-text">
                </div>
                <!-- DROPDOWN TO SELECT CORRESPONDING CATEGORY -->
                <!-- DYNAMIC SECTIONS THAT HAVE A TEXT SECTION EXPLAINING THE RULE AND AN EXAMPLE SECTION 
                FOR POTENTIAL EXAMPLES -->
                <div class="form-section" id="form-section-1">
                    <div class="sectionHeader">
                        <h3>Τμήμα Κανόνα</h3>
                    </div>
                    <label for="rule-section-1-text">Κείμενο Τμήματος</label>
                    <input type="text" name="rule-section-1-text" class="rule-section-text" key="1">
                    <label for="rule-section-1-example">Παράδειγμα Τμήματος</label>
                    <input type="text" name="rule-section-1-example" class="rule-section-example" key="1">
                </div>
                <div class="form-buttons">
                    <a class="button" onclick="addSectionInput()">Προσθήκη Τμήματος</a>
                    <a class="button green" onclick="submitForm()">Ολοκλήρωση</a>
                </div>
            </form>
        </div>
        <div class="format-helper">
            <table>
                <tr>
                    <td>!!κείμενο!! <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-1">κείμενο</td>
                </tr>
                <tr>
                    <td>**κείμενο** <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-2">κείμενο</td>
                </tr>
                <tr>
                    <td>$κείμενο$ <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-3">κείμενο</td>
                </tr>
                <tr>
                    <td>#κείμενο# <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-4">κείμενο</td>
                </tr>
                <tr>
                    <td>%κείμενο% <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-5">κείμενο</td>
                </tr>
                <tr>
                    <td>^κείμενο^ <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-6">κείμενο</td>
                </tr>
                <tr>
                    <td>&κείμενο& <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-7">κείμενο</td>
                </tr>
                <tr>
                    <td>@κείμενο@ <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-8">κείμενο</td>
                </tr>
                <tr>
                    <td>?κείμενο? <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-9">κείμενο</td>
                </tr>
                <tr>
                    <td>[κείμενο] <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-10 test">κείμενο</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- <div class="form-section" id="form-section-1">
        <h3>Τμήμα Κανόνα</h3>
        <label for="rule-section-1-text">Κείμενο Τμήματος</label>
        <input type="text" name="rule-section-1-text" class="rule-section-text" key="1">
        <label for="rule-section-1-example">Παράδειγμα Τμήματος</label>
        <input type="text" name="rule-section-1-example" class="rule-section-example" key="1">
    </div> -->
    <script>
        let form = document.querySelector("form");
        let formButtons = document.querySelector(".form-buttons");
        // let submitFormBtn = document.querySelector(".form-buttons").lastElementChild;

        function addSectionInput() {
            let formSectionCount = document.querySelectorAll(".form-section").length;

            let formSection = document.createElement("div");
            formSection.classList.add("form-section");
            formSection.setAttribute("id", `form-section-${formSectionCount + 1}`);

            let sectionHeader = document.createElement("div");
            sectionHeader.classList.add("section-header");
            let header = document.createElement("h3");
            header.innerText = "Τμήμα Κανόνα";

            let delBtn = document.createElement("a");
            delBtn.classList.add("button", "red");
            delBtn.innerText = "Διαγραφή";
            delBtn.addEventListener("click", () => {
                deleteFormSection(formSectionCount + 1);
            });

            sectionHeader.appendChild(header);
            sectionHeader.appendChild(delBtn);

            let inputElement1 = document.createElement("input");
            let labelElement1 = document.createElement("label");
            let inputElement2 = document.createElement("input");
            let labelElement2 = document.createElement("label");


            labelElement1.setAttribute("for", `rule-section-${formSectionCount + 1}-text`);
            labelElement1.innerText = "Κείμενο Τμήματος";
            inputElement1.setAttribute("name", `rule-section-${formSectionCount + 1}-text`);
            inputElement1.setAttribute("type", "text");
            inputElement1.setAttribute("key", `${formSectionCount + 1}`);
            inputElement1.classList.add("rule-section-text");

            labelElement2.setAttribute("for", `rule-section-${formSectionCount + 1}-example`);
            labelElement2.innerText = "Παράδειγμα Τμήματος";
            inputElement2.setAttribute("name", `rule-section-${formSectionCount + 1}-example`);
            inputElement2.setAttribute("type", "text");
            inputElement2.setAttribute("key", `${formSectionCount + 1}`);
            inputElement2.classList.add("rule-section-example");

            formSection.appendChild(sectionHeader);
            formSection.appendChild(labelElement1);
            formSection.appendChild(inputElement1);
            formSection.appendChild(labelElement2);
            formSection.appendChild(inputElement2);

            formButtons.remove();

            form.appendChild(formSection);
            form.appendChild(formButtons);
        }

        function deleteFormSection(idToDelete) {
            let elementToDelete = document.querySelector(`#form-section-${idToDelete}`);
            elementToDelete.remove();
        }
    </script>
</body>

</html>