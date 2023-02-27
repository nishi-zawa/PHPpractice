function validate() {
    // エラーメッセージ削除
    document.getElementById("errorMsg").innerHTML = ""
    var errorMsgList = document.getElementById("errorMsgList")
    if (null != errorMsgList) {
        while(errorMsgList.firstChild) {
            errorMsgList.removeChild(errorMsgList.firstChild)
        }
    }

    var empId = document.getElementById("empID").value
    var firstName = document.getElementById("empFirstname").value
    var lastName = document.getElementById("empLastname").value
    var empSec = document.getElementById("empSec").value
    var mail = document.getElementById("empMail").value
    // ラジオボタンが上手く取れないので↓だけ取り方違う
    var gender = document.querySelector('[name="empGender"]:checked')

    var submitFlg = true
    submitFlg = empIdValidate(empId, submitFlg)
    submitFlg = firstNameValidate(firstName, submitFlg)
    submitFlg = lastNameValidate(lastName, submitFlg)
    submitFlg = empSecValidate(empSec, submitFlg)
    submitFlg = mailValidate(mail, submitFlg)
    submitFlg = genderValidate(gender, submitFlg)

    return submitFlg
}

function empIdValidate(empId, submitFlg) {
    var msg = ""
    if (!empId) {
        msg = "社員IDを入力してください"
    } else if (empId.length !== 10) {
        msg = "社員IDは10文字で入力してください"
    } else if (empId.match("^YZ[0-9]{8}") == null) {
        msg = "社員IDを正しく入力してください"
    }
    var result = errorChk(msg, submitFlg)
    return result
}

function firstNameValidate(firstName, submitFlg) {
    var msg = ""
    if (!firstName) {
        msg = "社員名（姓）を入力してください"
    } else if (firstName.length > 20) {
        msg = "社員名（姓）は20文字以内で入力してください"
    }
    var result = errorChk(msg, submitFlg)
    return result
}

function lastNameValidate(lastName, submitFlg) {
    var msg = ""
    if (!lastName) {
        msg = "社員名（名）を入力してください"
    } else if (lastName.length > 20) {
        msg = "社員名（名）は20文字以内で入力してください"
    }
    var result = errorChk(msg, submitFlg)
    return result
}

function empSecValidate(empSec, submitFlg) {
    var msg = ""
    if (!empSec) {
        var msg = "所属セクションを選択してください"
    }
    var result = errorChk(msg, submitFlg)
    return result
}

function mailValidate(mail, submitFlg) {
    var msg = ""
    if (!mail) {
        msg = "メールアドレスを入力してください"
    } else if (mail.length > 256) {
        msg = "メールアドレスは256文字以内で入力してください"
    } else if (mail.match("^[a-zA-Z0-9._-]{1,}@[a-zA-Z0-9._-]{1,}$") == null) {
        msg = "メールアドレスを正しく入力してください"
    }
    var result = errorChk(msg, submitFlg)
    return result
}

function genderValidate(gender, submitFlg) {
    var msg = ""

    if (!gender) {
        msg = "性別を選択してください"
    }
    var result = errorChk(msg, submitFlg)
    return result
}

function errorChk(msg, submitFlg) {
    // submit予定ありかつエラーメッセージある時
    if (submitFlg && msg) {
        document.getElementById("errorMsg").innerHTML = msg
        return false
    }
    return submitFlg

}

function deleteBtnClick() {
    return window.confirm("データを削除します。\nよろしいですか？");
}