# Heroku MCGG Buildpack
### Chạy - Quản lý server minecraft qua giao diện web + free VPS!!!

This is a [Heroku Buildpack](https://devcenter.heroku.com/articles/buildpacks)
for running a Minecraft server in a [dyno](https://devcenter.heroku.com/articles/dynos).

[![Deploy to Heroku](https://www.herokucdn.com/deploy/button.png)](https://heroku.com/deploy)

## Usage

Tạo [tài khoản ngrok](https://ngrok.com/) và copy cái API Token

Tạo [tài khoản dropbox](https://dropbox.com/) và copy OAUTH Token từ trang developers

Tạo [tài khoản UptimeRobot](https://uptimerobot.com/) request HTTP tên miền của cái app của bạn (nhằm để giữ app không ngủ - nếu app không nhận được request HTTP nào sau 30p, nó sẽ ngủ)

### Xong rồi nhấp vào cái nút trên!

## Cảnh báo
- Acc ko có thẻ visa chỉ có 550 giờ free dyno (không đủ 1 tháng server chạy 24/7)

- Thêm thẻ Visa (ảo) sẽ có thêm 450 giờ, tổng 1000 giờ, hơn cả đủ để chạy 1 dyno

- Nếu hết free dyno hours thì bạn sẽ ko thể chạy server nữa, đến hết tháng

- Free dyno chỉ có max 1024MB Ram, nếu dyno chạy quá 512MB thì logs sẽ báo lỗi R14

- Khi đạt đỉnh 1024MB hoặc hơn, lỗi R15 sẽ xuất hiện và dyno sẽ bị buộc reset

- Dyno sẽ reset ÍT NHẤT 1 lần trong vòng lặp 24h, sau khi reset, vòng lặp đó sẽ reset


