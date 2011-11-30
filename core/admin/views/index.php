<?php View::insert('includes/header'); ?>

    <div class="grid_3">
        <div class="box">
            <h2>Navigation</h2>
            <ul class="menu">
                <li>
                    <a href="#">Item 1</a>
                </li>
                <li>
                    <a href="#">Item 2</a>
                </li>
                <li>
                    <a href="#">Item 3</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="grid_9">
        <div class="box">
            <h2>Table</h2>
            <div class="block">
                <table>
                    <thead>
                        <tr>
                            <th>Column 1</th>
                            <th>Column 2</th>
                            <th class="currency">Column 3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="odd">
                            <th>Lorem ipsum</th>
                            <td>Dolor sit</td>
                            <td class="currency">$125.00</td>
                        </tr>
                        <tr>
                            <th>Dolor sit</th>
                            <td>Nostrud exerci</td>
                            <td class="currency">$75.00</td>
                        </tr>
                        <tr class="odd">
                            <th>Nostrud exerci</th>
                            <td>Lorem ipsum</td>
                            <td class="currency">$200.00</td>
                        </tr>
                        <tr>
                            <th>Lorem ipsum</th>
                            <td>Dolor sit</td>
                            <td class="currency">$64.00</td>
                        </tr>
                        <tr class="odd">
                            <th>Dolor sit</th>
                            <td>Nostrud exerci</td>
                            <td class="currency">$36.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box">
            <h2>Form</h2>
            <div class="block">
                <form action="">
                    <fieldset>
                        <legend>Login Information</legend>
                        <p>
                            <label>Username: </label>
                            <input type="text" name="username" value="" />
                        </p>
                        <p>
                            <label>Password: </label>
                            <input type="password" name="password" />
                        </p>
                        <p>
                            <label>Re-type Password: </label>
                            <input type="password" name="password2" />
                        </p>
                    </fieldset>

                    <fieldset>
                        <legend>Personal Information</legend>
                        <p>
                            <label>First Name: </label>
                            <input type="text" name="first-name" value="" />
                        </p>
                        <p>
                            <label>Last Name: </label>
                            <input type="text" name="last-name" value="" />
                        </p>
                        <p>
                            <label>Address: </label>
                            <input type="text" name="address1" value="" />
                        </p>
                        <p>
                            <label>Address 2: </label>
                            <input type="text" name="address2" value="" />
                        </p>
                        <p>
                            <label>City: </label>
                            <input type="text" name="city" value="" />
                        </p>
                        <p>
                            <label>State/Province: </label>
                            <select name="State">
                                <option value="">Select State...</option>
                            </select>
                        </p>
                        <p>
                            <label>Country: </label>
                            <select name="Country">
                                <option value="">Select Country...</option>
                                <option value="Canada">Canada</option>
                                <option value="United States">United States</option>
                            </select>
                        </p>
                        <p>
                            <label>Zip/Postal Code: </label>
                            <input type="text" name="zipcode" value="" />
                        </p>
                        <p>
                            <label>Phone Number: </label>
                            <input type="text" name="phone" value="" />
                        </p>
                        <p>
                            <label>Email Address: </label>
                            <input type="text" name="email" value="" />
                        </p>
                        <input type="submit" value="Register" class="register-button" />
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

<?php View::insert('includes/footer'); ?>
