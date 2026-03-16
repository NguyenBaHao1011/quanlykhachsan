const { Builder, By, Key, until } = require('selenium-webdriver');

async function loginFlowTest() {
    let driver = await new Builder().forBrowser('chrome').build();
    try {
        console.log("Starting Selenium UI Test...");
        
        // Navigate to login page
        await driver.get('http://localhost:8000/login.html');
        
        // Fill login form
        await driver.findElement(By.css('input[type="text"]')).sendKeys('admin');
        await driver.findElement(By.css('input[type="password"]')).sendKeys('password');
        
        // Select role (if necessary)
        let selectElement = await driver.findElement(By.id('role'));
        await selectElement.findElement(By.css('option[value="admin"]')).click();
        
        // Click login button
        await driver.findElement(By.className('btn-login')).click();
        
        // Wait for redirect to admin dashboard
        await driver.wait(until.urlContains('admin/index.html'), 5000);
        console.log("Login Flow Test: PASSED");
        
    } catch (e) {
        console.error("Login Flow Test: FAILED", e);
    } finally {
        await driver.quit();
    }
}

loginFlowTest();
