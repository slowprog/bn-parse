<?php require VIEWS . '/_common/header.php'; ?>

<div class="container">
    <h2>Поиск квартир</h2>

    <form action="<?php echo URL; ?>" method="POST">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Станция метро:</label>

                    <select size="6" multiple class="form-control" name="stations_selected[]">
                        <?php foreach ($stations as $id => $data) : ?>
                            <option value="<?php echo $id ?>" <?php echo in_array($id, $stationsSelected) ? 'selected' : '' ?>><?php echo $data['title'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Комнат от:</label>
                            <input type="text" class="form-control" name="flat_from" value="<?php echo htmlspecialchars($flatFrom, ENT_QUOTES, 'UTF-8'); ?>" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Комнат до:</label>
                            <input type="text" class="form-control" name="flat_to" value="<?php echo htmlspecialchars($flatTo, ENT_QUOTES, 'UTF-8'); ?>" required />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Цена от:</label>
                            <input type="text" class="form-control" name="price_from" value="<?php echo htmlspecialchars($priceFrom, ENT_QUOTES, 'UTF-8'); ?>" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Цена до:</label>
                            <input type="text" class="form-control" name="price_to" value="<?php echo htmlspecialchars($priceTo, ENT_QUOTES, 'UTF-8'); ?>" required />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="submit" class="btn btn-primary" name="submit" value="Поиск" />
    </form>
</div>

<div class="spinner-block" style="display: none;">
    <div class="spinner">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
</div>

<div class="container">
    <div class="result">
        <?php if ($submit) : ?>
            <?php if ($flats) : ?>
                <h2>Список квартир</h2>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Издание</th>
                            <th>Кол-во комнат</th>
                            <th>Адрес</th>
                            <th>Метро</th>
                            <th>Этаж</th>
                            <th>Тип дома</th>
                            <th>Площадь (общая, жилая, кухня)</th>
                            <th>Телефон</th>
                            <th>Санузел</th>
                            <th>Субъект</th>
                            <th>Контакт</th>
                            <th>Доп. сведения</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($flats as $flat) : ?>
                        <tr>
                            <td>
                                <a href="http://www.bn.ru/detail/flats/<?php echo $flat['id']; ?>/">
                                    <?php echo $flat['id']; ?>
                                </a>
                            </td>
                            <td>
                                <?php echo $flat['publication'] ?? '—'; ?>
                            </td>
                            <td>
                                <?php echo $flat['room'] ?? '—'; ?>
                            </td>
                            <td>
                                <?php echo $flat['address'] ?? '—'; ?>
                            </td>
                            <td>
                                <?php
                                    if ($flat['metro']) {
                                        echo implode(', ', $flat['metro']);
                                    } else {
                                        echo '—';
                                    }
                                ?>
                            </td>
                            <td>
                                <?php echo $flat['floor'] ?? '—'; ?>
                            </td>
                            <td>
                                <?php echo $flat['house_type'] ?? '—'; ?>
                            </td>
                            <td>
                                <?php
                                    echo ($flat['square_total'] ?? '—')
                                        . (', ' . $flat['square_live']  ?? '—')
                                        . (', ' . $flat['square_kitchen'] ?? '—');
                                ?>
                            </td>
                            <td>
                                <?php echo $flat['phone'] ?? '—'; ?>
                            </td>
                            <td>
                                <?php echo $flat['bathroom'] ?? '—'; ?>
                            </td>
                            <td>
                                <?php echo $flat['subject'] ?? '—'; ?>
                            </td>
                            <td>
                                <?php echo $flat['contact'] ?? '—'; ?>
                            </td>
                            <td>
                                <?php echo $flat['addition'] ?? '—'; ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            <?php else : ?>
                <h2>Квартир нет</h2>
            <?php endif ?>
        <?php endif ?>
    </div>
</div>

<script>
    $(document).on('click', 'form input[type="submit"]', function (e) {
        e.preventDefault();
        e.stopPropagation();

        $('.spinner-block').show();

        let $form = $(this).closest('form');
        // let data = {};
        // data[$company.attr('name')] = $company.val();

        $.ajax({
            url  : $form.attr('action'),
            type : $form.attr('method'),
            data : $form.serialize() + '&submit=1',
            success : function (html) {
                $('.result').replaceWith(
                    $(html).find('.result')
                );
                $('.spinner-block').hide();
            }
        });
    });
</script>

<?php require VIEWS . '/_common/footer.php'; ?>
