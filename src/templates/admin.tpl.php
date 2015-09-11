<div class="wrap">
    <h2>Vimeo Videos</h2>

    <form method="POST">
        <input type="hidden" name="option_page" value="46cl-vimeo-videos">

        <table class="form-table">
            <tbody>
            <!-- Token -->
            <tr>
                <th>
                    <label for="endpoint">API Token</label>
                </th>
                <td>
                    <input id="endpoint" class="regular-text" type="text" name="46cl_vimeo_token"
                           value="<?php $o('46cl_vimeo_token'); ?>">
                </td>
            </tr>
            </tbody>
        </table>

        <p class="submit">
            <input class="button button-primary" type="submit" value="Enregistrer">
        </p>
    </form>
</div>