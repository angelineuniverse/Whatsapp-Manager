import wppconnect from '@wppconnect-team/wppconnect';

async function startSession() {
    return wppconnect.create({
        session: 'AngelineSupport',
        catchQR: (base64Qrimg, asciiQR, attempts, urlCode) => {
            console.log(asciiQR);
        },
        statusFind: (statusSession, session) => {
            console.log('Status Session: ', statusSession); //return isLogged || notLogged || browserClose || qrReadSuccess || qrReadFail || autocloseCalled || desconnectedMobile || deleteToken
            //Create session wss return "serverClose" case server for close
            console.log('Session name: ', session);
        },
        headless: true, // Headless chrome
        devtools: false, // Open devtools by default
        useChrome: true, // If false will use Chromium instance
        debug: true, // Opens a debug session
        logQR: true, // Logs QR automatically in terminal
        browserArgs: [''], // Parameters to be added into the chrome browser instance
        deviceName: 'Angeline Support',
        createPathFileToken: false,
        whatsappVersion: '2.3000.1015950991-alpha',
        puppeteerOptions: {
            userDataDir: "./src/module/Whatsapp/tokens"
        }, // Will be passed to puppeteer.launch
        disableWelcome: true, // Option to disable the welcoming message which appears in the beginning
        updatesLog: true, // Logs info updates automatically in terminal
        autoClose: 120000, // Automatically closes the wppconnect only when scanning the QR code (default 60 seconds, if you want to turn it off, assign 0 or false)
    }).then((res) => {
        console.log(res,"response");
    }).catch((err) => {
        console.log(err,"error");
    })
}

export { startSession };