<?php
$this->data['header'] = $this->t('{login:user_pass_header}');

if (strlen($this->data['username']) > 0) {
    $this->data['autofocus'] = 'password';
} else {
    $this->data['autofocus'] = 'username';
}

$this->includeAtTemplateBase('includes/header_custom.php');

?>

<div class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <a href="https://laravel.accomodati.it">
                <svg class="w-16 h-16" viewbox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.395 44.428C4.557 40.198 0 32.632 0 24 0 10.745 10.745 0 24 0a23.891 23.891 0 0113.997 4.502c-.2 17.907-11.097 33.245-26.602 39.926z" fill="#6875F5" />
                    <path d="M14.134 45.885A23.914 23.914 0 0024 48c13.255 0 24-10.745 24-24 0-3.516-.756-6.856-2.115-9.866-4.659 15.143-16.608 27.092-31.75 31.751z" fill="#6875F5" />
                </svg>
            </a>
            <!--
            <p class="mt-6 text-center text-gray-500">
                <h2 style="break: both"><?php echo $this->t('{login:user_pass_header}'); ?></h2>
                <p class="logintext"><?php echo $this->t('{login:user_pass_text}'); ?></p>
            </p>
            -->
        </div>
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">

            <?php if ($this->data['errorcode'] !== null) { ?>
                <div class="mb-4">
                    <div class="font-medium text-red-600"><?php echo $this->t('{login:error_header}'); ?></div>
                    <div class="font-medium text-red-600"><?php  echo htmlspecialchars($this->t($this->data['errorcodes']['title'][$this->data['errorcode']], $this->data['errorparams'])); ?></div>
                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                        <li><?php echo htmlspecialchars($this->t($this->data['errorcodes']['descr'][$this->data['errorcode']], $this->data['errorparams'])); ?></li>
                    </ul>
                    <?php
                        if (0) {
                            echo '<p><pre>errorcode: ' . print_r($this->data['errorcode'], 1) . '</pre></p>';
                            //echo '<p><pre>errorcodes: ' . print_r($this->data['errorcodes'], 1) . '</pre></p>';
                            echo '<p><pre>errorparams: ' . print_r($this->data['errorparams'], 1) . '</pre></p>';
                        }
                    ?>
                </div>
            <?php } ?>

            <form action="?" method="post" id="f" name="f">

                <input type="hidden" id="processing_trans" value="<?php echo $this->t('{login:processing}'); ?>" />
                <?php foreach ($this->data['stateparams'] as $name => $value) { echo '<input type="hidden" name="'.htmlspecialchars($name).'" value="'.htmlspecialchars($value).'" />'; } ?>

                <div>
                    <label class="block font-medium text-sm text-gray-700" for="username">Email</label>
                    <input type="email" id="username" name="username" required="required" autofocus="autofocus" value="<?php echo htmlspecialchars($this->data['username']); ?>"
                        <?php echo ($this->data['forceUsername']) ? 'disabled="disabled"' : 'autocomplete="username" tabindex="1"'; ?>
                        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full"
                    />
                </div>

                <div class="mt-4">
                    <label class="block font-medium text-sm text-gray-700" for="password">Password</label>
                    <input type="password" id="password" name="password" value="" tabindex="2" autocomplete="current-password" required="required"
                        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 w-full"
                    />
                </div>

                <div class="block mt-4">
                    <?php if ($this->data['rememberUsernameEnabled'] && !$this->data['forceUsername']) { // display the "remember my username" checkbox ?>
                        <label for="remember_username" class="flex items-center">
                            <input type="checkbox"
                                id="remember_username"
                                name="remember_username"
                                tabindex="4"
                                <?php echo ($this->data['rememberUsernameChecked']) ? 'checked="checked"' : ''; ?>
                                value="Yes"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            />
                            <span class="ml-2 text-sm text-gray-600"><?php echo $this->t('{login:remember_username}'); ?></span>
                        </label>
                    <?php } ?>

                    <?php if ($this->data['rememberMeEnabled']) { // display the remember me checkbox (keep me logged in) ?>
                        <label for="remember_me" class="flex items-center">
                            <input type="checkbox"
                                    id="remember_me"
                                    name="remember_me"
                                    tabindex="5"
                                    <?php echo ($this->data['rememberMeChecked']) ? 'checked="checked"' : ''; ?>
                                    value="Yes"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            />
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                    <?php } ?>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="https://laravel.accomodati.it/forgot-password">Forgot your password?</a>
                    <button type="submit" id="submit_button" tabindex="6" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-4">Log in</button>
                </div>

                <div class="flex items-start mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="https://laravel.accomodati.it/register">Register!</a>
                </div>

            </form>
        </div>
    </div>
</div>

<?php

$this->includeAtTemplateBase('includes/footer_custom.php');
