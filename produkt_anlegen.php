<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dunkles Produktformular</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #1e1e1e; /* Dunkles Grau */
      color: #ccc;
      margin: 0;
      padding: 2rem;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    form {
      background-color: #2a2a2a; /* Etwas heller als Body */
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.5);
      width: 100%;
      max-width: 700px;
    }

    h2 {
      text-align: center;
      margin-bottom: 2rem;
      color: #f1f1f1;
    }

    .form-row {
      display: flex;
      align-items: center;
      margin-bottom: 1.2rem;
      flex-wrap: wrap;
    }

    .form-row label {
      width: 180px;
      font-weight: 600;
      color: #ddd;
    }

    .form-row input {
      flex: 1;
      padding: 0.5rem;
      border: 1px solid #444;
      background-color: #3a3a3a;
      color: #eee;
      border-radius: 6px;
    }

    .form-row input:focus {
      border-color: #5fa8d3;
      outline: none;
      background-color: #444;
    }

    .submit-row {
      text-align: center;
      margin-top: 2rem;
    }

    button {
      background-color:rgb(98, 101, 102);
      color: white;
      border: none;
      padding: 0.75rem 2rem;
      font-size: 1rem;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #3b7ca7;
    }

    @media (max-width: 600px) {
      .form-row {
        flex-direction: column;
        align-items: flex-start;
      }

      .form-row label {
        width: 100%;
        margin-bottom: 0.5rem;
      }

      .form-row input {
        width: 100%;
      }
    }
  </style>
</head>
<body>
   
  <form id="produktForm">
    <h2>Produktinformationen</h2>
    <!--Button  -->
    <div id="logout">
      <button type="submit">Logout</button>
    </div>

    <div class="form-row">
      <label for="p_code">Produkt Code:</label>
      <input type="text" id="p_code" name="p_code" required>
    </div>

    <div class="form-row">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required>
    </div>

    <div class="form-row">
      <label for="linie">Linie:</label>
      <input type="text" id="linie" name="linie">
    </div>

    <div class="form-row">
      <label for="massstab">Maßstab:</label>
      <input type="text" id="massstab" name="massstab">
    </div>

    <div class="form-row">
      <label for="lieferant">Lieferant:</label>
      <input type="text" id="lieferant" name="lieferant">
    </div>

    <div class="form-row">
      <label for="details">Details:</label>
      <input type="text" id="details" name="details">
    </div>

    <div class="form-row">
      <label for="extra1">Zusätzliche Info 1:</label>
      <input type="text" id="extra1" name="extra1">
    </div>
    

    <!--Button  -->
    <div class="submit-row">
      <button type="submit">Anlegen</button>
    </div>
  </form>

</body>
</html>
