import nodemailer from "nodemailer";

export default async function handler(req, res) {
  if (req.method === "POST") {
    const { name, email, message } = req.body;

    let transporter = nodemailer.createTransport({
      service: "gmail",
      auth: {
        user: process.env.GMAIL_USER,   // Gmail address (env var)
        pass: process.env.GMAIL_PASS    // App Password (env var)
      }
    });

    let mailOptions = {
      from: email,
      to: process.env.GMAIL_USER,
      subject: "New Form Submission",
      html: `<p><b>Name:</b> ${name}</p>
             <p><b>Email:</b> ${email}</p>
             <p><b>Message:</b> ${message}</p>`
    };

    try {
      await transporter.sendMail(mailOptions);
      res.status(200).send("Message sent successfully!");
    } catch (error) {
      res.status(500).send("Error: " + error.message);
    }
  } else {
    res.status(405).send("Method Not Allowed");
  }
}
