# lightremote
Control Philips Hue lights from your web browser.

## About
Philips Hue is a range of smart light devices used in many homes. Philips provides a mobile application for
controlling their lights; however, there is no official web interface for controlling Hue lights and only a few third-party ones.
I made this web app because I am interested in IoT devices and wanted to fill the need for a Hue web interface. 
I also use this app to control the lights in my own house.

### Philips Hue API
This project uses the Philips Hue Remote API to control Hue lights remotely. First, the user is directed to grant LightRemote access to their 
Philips Hue account. Then, Philips Hue will provide an access token that will be used in API requests.

### Front end
I used Bootstrap for styling the front end. Each room/light has its own Bootstrap card with a color picker, on/off toggle, and brightness slider. 
To implement interactivity, each card has event handlers which are defined in lights-ajax.js and rooms-ajax.js.

### Back end
The back end is written in PHP without a framework. The back end makes calls to the Philips Hue Remote API, adds new users to 
the database, adds a user's API access token to the database, and adds messages to the database.
