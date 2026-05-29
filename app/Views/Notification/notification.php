<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Management</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            padding: 30px;
        }

        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
        }

        .add-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        .add-btn:hover {
            background: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background: #343a40;
            color: white;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .active {
            background: #28a745;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
        }

        .inactive {
            background: #dc3545;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
        }

        .modal {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: white;
            width: 450px;
            margin: 8% auto;
            padding: 25px;
            border-radius: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .save-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
        }

        .close-btn {
            background: red;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 55px;
            height: 28px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 3px;
            background: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background: #28a745;
        }

        input:checked+.slider:before {
            transform: translateX(27px);
        }

        .back-btn {
            background: linear-gradient(45deg, #2563eb, #1d4ed8);
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all .3s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, .25);
        }

        .back-btn:hover {
            background: linear-gradient(45deg, #1d4ed8, #1e40af);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, .35);
        }

        .back-btn:active {
            transform: scale(.98);
        }
    </style>

</head>

<body>

    <div class="container">

        <div class="header">
            <h2>Notification Management</h2>

            <button class="add-btn" onclick="openModal()">
                + Add New Notification
            </button>
            <button class="back-btn" onclick="back()">
                ← Back
            </button>
        </div>

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Created Date</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!(empty($notification))):
                    $index = 1; ?>
                    <?php foreach ($notification as $notification): ?>
                        <tr>
                            <td><?= $index++; ?></td>
                            <td><?= $notification['title'] ?></td>
                            <td><?= $notification['description'] ?></td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox"
                                        class="status-toggle"
                                        <?= ($notification['status'] == 1) ? 'checked' : '' ?>
                                        data-id="<?= $notification['id'] ?>">
                                    <span class="slider"></span>
                                </label>
                            </td>
                            <td>
                                <?= date('d M Y h:i A', strtotime($notification['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <!-- Modal -->
        <div class="modal" id="notificationModal">
            <div class="modal-content">
                <h2>Create Notification</h2>
                <br>
                <form method="post" action="notification/create-notification">

                    <div class="form-group">

                        <label>Title</label>

                        <input type="text" name="title" placeholder="Enter title">

                    </div>

                    <div class="form-group">

                        <label>Description</label>

                        <textarea rows="4" name="description"
                            placeholder="Enter description"></textarea>

                    </div>

                    <div class="form-group">

                        <label>Status</label>

                        <select name="status">
                            <option>Active</option>
                            <option>Inactive</option>
                        </select>

                    </div>

                    <button class="save-btn">
                        Save Notification
                    </button>

                    <button type="button"
                        class="close-btn"
                        onclick="closeModal()">
                        Close
                    </button>

                </form>

            </div>

        </div>

        <script>
            function openModal() {
                document.getElementById('notificationModal')
                    .style.display = 'block';
            }

            function closeModal() {
                document.getElementById('notificationModal')
                    .style.display = 'none';
            }

            $(document).ready(function() {

                $(document).on(
                    'change',
                    '.status-toggle',
                    function() {
                        let id = $(this).data('id');
                        let status = $(this).prop('checked') ? 1 : 0;
                        $.ajax({
                            url: "http://localhost:8080/notification/update-status",
                            method: "POST",
                            data: {
                                id: id,
                                status: parseInt(status)
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.status) {
                                    Swal.fire({
                                        title: "Status Update!!",
                                        icon: "success",
                                        draggable: true
                                    }).then(() => {
                                        window.location.href =
                                            "<?= base_url('notification') ?>";
                                    });
                                }

                            },
                            error: function() {
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: "something went wrong",
                                });
                            }

                        });

                    });
            });

            function back() {
                window.history.back();
            }
        </script>

</body>

</html>