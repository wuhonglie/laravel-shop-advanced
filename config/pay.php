<?php

return [
    'alipay' => [
        'app_id'         => '2016101300675017',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqj+PpqEjumDaJrpjnvJsVxEtgHSVkjqUwUNmrrHU+JBjcTbDiLlqbgnat1Rtc8aa6vq174/72Hb6HY9z2Jq0HdPM3hy4KzS7pR137uzUnzu2v0UofLK2D6wWEcBFZ2S/tZQKeZDfpvdO/lYgGIGQLYs3AA0CNNniEKpitF+JgHFlGiO15FVTLix36S9XdesBFNwXkMn7q0BHQleAi9irQ0GZjDFHnh/3RDkP1g+JnpPUXH26NY1DQisL5E3v4ZeHmR42OgQfJ3JUPWTzOIJzaLa+sQ5JdV61Y0SO0es/gsDKgEARcXVVBgl+nN5mwX6p1XKtJB6Ux0VbL4m8zFF3NQIDAQAB',
        'private_key'    => 'MIIEpAIBAAKCAQEAtyK/dFu0TO6NQJCCDzdOFngx7QirH8Oni1d6oga41E+enKeT55LmB3ajLn3fO6V9rDNVJKK23+d8EaGhhYgHIHCNpBt+/8s3MTUH0Pa736noMOvZ5GZCzqxE8k9E84rSgKZoy3gLf5okMVIJhuP2qEMK1BArVN+kqPw7dO+0J1k5czA4URdZlIkble2RmatQOyWAVlFg4uGi9tppWzuScUQ0rzl0gUa3/wEpBAS+W8n4cMinvKRE+3miTN30ULIASMDe/xiW2DSozTCR6vizD6Q6No16OSf8c7QGbWf2UScniA43GOBf1J1o2ywr2x6/mu5hg50ikTPyzVz3LmjmZwIDAQABAoIBAQCZHOMnHJjVDUlppqgmop2V6a4MOIgiCYnl1SDBoI8dauf4n57oKQhLSyRJdZGTI9y7gTt5YtU0xCwV7aXGp6EVvSSfBSIcKMIfUPAVBIS1DjAD3tyQufK3Ko6GbEIAwJsYVokdPGOYrr39wlIWxjduHIdMH6yqzES1yBTJwrNDhUo7y3Z98ELT+1Yzd1o2/NBb38UdCZN3RGqzBwjUGUQNtDAO4QYFvX5bWf/7fm66mZmWT36AYTs3bS/z6J8a0x9/SdncSdE1UsEt251DBjRVve/UexLDy/QIDPQmGdHJxpR1uWXvDGjX+KOsOyVm6AIYtiX1gZzK9m0yJVFalyQBAoGBAOIzwyZSe1AOtUcWHekgLGzuGFqIJXPsgYpiNzrgMiJkhc5j2pvE7D0DJFKtOQNowprh6NmyeSLNEmqyXMcI7iamB4PmOeZ9h78e5xggtLcAsUWn6CE8GNw0viQ//SeRim/VWkuh4K0WDknpSQiQfx2/V+mvAGmFa/uUZh5HXuxnAoGBAM9CprfmZ4TVSR0a5mFSNlZi5v2OTOPL4gX/WtMw9bYUmeSGlHuumwoYuIFWMPELCFrma5PANb27aS1/TP+bsbFXXZqXOqhNr+Vdlha5VOL+H5l42wPlR33ILlX+nKoXxNTVAGd2COXs/25ivRz0sk6G535jGtThP+wBuSd1yfYBAoGAZnqi7+qwWS3ArOr1NBfSaKtZtzI1HZbJfWNPuEm4DOLTyBRvBuEpUd7phtdZnBTLDZZJgs8bmEumC1axN67xDetsPjKAis7WQB0E/2ZZ2QrkRTVzshLjBGiUuCGRVGrYeCAFn3xAKMp0QMICx3GHVXfJoWIaYrQCnuC1fwRAmgMCgYAfxthI2cqFUy4iiD1KLpCmLgO8XFTtmBTPVpdWSetiP0ZZA0lArUSYB7JCIjTewBJH3Ywg2xdiP5mgoCPuDLDxv7NaQFV9+Gs/f1nDoiahptSxcxYhQsXzQv3XsXHTolFLUFz/f9ldZzjCc+EKz3mHEKfteMRkXyStypXYtvaAAQKBgQCgRzEEWfJIBvWK4SdNRtoL4tebdPTGOY/MnSoVrR6J5Ku8F/MxCjbUWC9+p0083NyKXFD10Sa6Xud3BNDg1JjAxKpYHKXxMuYZeljaMJfxMxcmG4dc3lcJvIgTBHJy3dAqg/QucLvgp47O5nEIECKKVZQVnQg/7KTj22pVtkc9Ig==',
        'log'            => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id'      => '',
        'mch_id'      => '',
        'key'         => '',
        'cert_client' => '',
        'cert_key'    => '',
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];
