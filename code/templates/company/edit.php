<?php
/**
 * @var $regions;
 * @var $countries;
 * @var $company;
 */
?>

<div class="bg-dark h-100">
    <div class="container">
        <h1>Редактирование компании</h1>
    </div>
    <form action="/companies/update/<?=$company['id'] ?>" method="post" class="w-50">
        <div class="form-group mt-1">
            <label for="company_name">Название компании:<?=htmlspecialchars($company['company_name']) ?></label>
            <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo htmlspecialchars($company['company_name']); ?>" required />
        </div>
        <div class="form-group mt-1">
            <label for="company_bin">БИН компании:</label>
            <input type="text" class="form-control" id="company_name" name="company_bin" value="<?=$company['company_bin']; ?>" required />
        </div>
        <div class="form-group mt-3">
            <label for="region">Страна:</label>
            <select name="region" id="role" class="form-control">
                <?php foreach ($countries as $country): ?>
                    <option value="<?=$country['id'];?>" <?php echo $company['country']===$country['id'] ? 'selected': '';?>>
                        <?=$country['name'];?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group mt-3">
            <label for="region">Регион:</label>
            <select name="region" id="role" class="form-control">
                <?php foreach ($regions as $region): ?>
                    <option value="<?=$region['id'];?>" <?php echo $company['region']===$region['id'] ? 'selected': '';?>>
                        <?=$region['region_name'];?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group mt-1">
            <label for="address">Адрес:</label>
            <input type="text" class="form-control" id="address" name="address" value="<?=$company['address']; ?>" required />
        </div>
        <div class="form-group mt-1">
            <label for="phone">Телефон:</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?=$company['phone']; ?>" required />
        </div>
        <div class="form-group mt-1">
            <label for="otrasl">Отрасли деятельности:</label>
            <input type="text" class="form-control" id="otrasl" name="otrasl" value="<?=$company['otrasl']; ?>" required />
        </div>
        <div class="form-group mt-1">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?=$company['email']; ?>" required/>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Save changes</button>
    </form>
</div>

