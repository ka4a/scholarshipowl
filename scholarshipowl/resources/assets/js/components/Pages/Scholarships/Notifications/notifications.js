export default {
    "success": {
      message: "Well Done!",
      notification: "<p>your application</p> <p>was submitted!</p>",
      controller: {
        style: "button",
        text: "continue applying now!",
        action: "DETAILS"
      },
      imageName: "success",
      imageFormat: "png"
    },
    "no-matches": {
      message: "That’s too bad – no ma‌tch‌es!",
      notification: "<p>Change your query</p> <p>and give it another shot</p>",
      imageName: "no-matches",
      imageFormat: "png"
    },
    "failure": {
      message: "<span>Oopsy,</span> your application was not sent!",
      notification: "give it another try",
      imageName: "failure"
    },
    "freemium-success": {
      message: "",
      notification: "",
      imageName: "success",
      imageFormat: "png",
      showBackButton: 1,
      controller: {
        action: "LIST"
      }
    },
    "freemium-no-credits": {
      message: "",
      notification: "",
      imageName: "no-credits",
      imageFormat: "png"
    },
    "no-favourites": {
      message: "You’ve got some choices to make.",
      text: "and pick your favorite scholarships.",
      imageName: "no-favourites",
      controller: {
        text: "Go to <span>New</span> tab",
        action: "NEW"
      },
      imageFormat: "png"
    },
    "no-sent": {
      message: "Hoot! Nothing in there!",
      text: "and send some applications!",
      imageName: "no-sent",
      controller: {
        text: "Go to <span>Favorite</span> tab",
        action: "FAVORITES"
      },
      imageFormat: "png"
    },
    "no-new": {
      message: "Every accomplishment starts <p>with a choice -</p> you've already made yours!",
      text: "and complete your applications.",
      imageName: "no-new",
      controller: {
        text: "Go to <span>Favorite</span> tab",
        action: "FAVORITES"
      },
      imageFormat: "png"
    },
    "no-authorized": {
      message: "Oopsy, Your Session has Ended!",
      text: "Please, Login Again and Continue Applying",
      imageName: "failure",
    },
    "won": {
      message: "Congratulations!",
      notification: "You Won!",
      text: "Click below to claim your award!",
      controller: {
        style: "button",
        text: "claim",
        action: "EXTERNAL",
        link: "",
        inNewTab: 1,
      },
      imageName: "won",
      imageFormat: "gif",
      closable: 1
    },
    "missed": {
      message: "You Snooze, You Lose!",
      notification: "You Have Failed to Claim Your Award in Time",
      text: "Check the status of your applications regularly.",
      controller: {
        style: "button",
        text: "continue applying",
        action: "NEW"
      },
      imageName: "missed",
      imageFormat: "png",
      closable: 1
    },
    "awarded": {
      message: "Congratulations!",
      notification: "You Won!",
      controller: {
        style: "button",
        text: "continue applying",
        action: "NEW"
      },
      imageName: "won",
      imageFormat: "gif",
      closable: 1
    },
    "winner-chosen": {
      message: "Great News!",
      notification: "The Winner Has Been Chosen!",
      text: "Maybe it's you?",
      controller: {
        style: "button",
        text: "see who won",
        action: "EXTERNAL",
        link: "/awards/scholarship-winners"
      },
      imageName: "winner-choosen",
      imageFormat: "png",
      closable: 1
    },
    "no-requirements": {
      message: "You're in Luck!",
      notification: "This Application is Ready to be submitted!",
      text: "Click Apply to submit the Application!",
      imageName: "no-requirements",
      imageFormat: "png"
    },
    "no-mails": {
      message: "Hoot! Nothing in there!",
      imageName: "no-sent",
      imageFormat: "png"
    }
  }