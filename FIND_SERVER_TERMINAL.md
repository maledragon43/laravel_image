# How to Find the Terminal Running `php artisan serve`

## Method 1: Check Task Manager (Easiest)

1. **Press `Ctrl + Shift + Esc`** to open Task Manager
2. Go to the **"Details"** tab
3. Look for processes named:
   - `php.exe`
   - `php.exe` with command line showing `artisan serve`
4. Right-click on it → **"Go to details"** or check the **"Command line"** column
5. You can also right-click → **"End task"** to stop it

## Method 2: Check Running Port

The server runs on port 8000 by default. Check what's using it:

### In PowerShell:
```powershell
# Find what's using port 8000
netstat -ano | findstr :8000

# This will show the Process ID (PID)
# Then find the process:
Get-Process -Id <PID>
```

### In Command Prompt:
```cmd
netstat -ano | findstr :8000
tasklist | findstr <PID>
```

## Method 3: Find PHP Processes

### In PowerShell:
```powershell
# List all PHP processes
Get-Process php -ErrorAction SilentlyContinue | Format-Table Id, ProcessName, Path -AutoSize

# Or more detailed:
Get-WmiObject Win32_Process | Where-Object {$_.Name -eq "php.exe"} | Select-Object ProcessId, CommandLine
```

### In Command Prompt:
```cmd
tasklist | findstr php.exe
```

## Method 4: Visual Identification

Look for a terminal window that shows:
- Text like: `INFO  Server running on [http://127.0.0.1:8000]`
- Or: `Laravel development server started`
- The window title might show the path or "artisan"

**Tip:** If you have multiple terminal windows:
- Look for one that's actively showing server logs
- It might be minimized in the taskbar
- Check all open PowerShell/CMD/Windows Terminal windows

## Method 5: Stop All PHP Processes (Nuclear Option)

If you can't find the specific terminal:

### In PowerShell (as Administrator):
```powershell
# Stop all PHP processes
Stop-Process -Name php -Force

# Then restart the server
cd D:\XAMPP\htdocs\laravel_image
D:\xampp\php\php.exe artisan serve
```

### In Command Prompt (as Administrator):
```cmd
taskkill /F /IM php.exe
```

**Warning:** This will stop ALL PHP processes, not just the Laravel server.

## Method 6: Check All Open Terminal Windows

1. **Press `Alt + Tab`** to cycle through open windows
2. Look for terminal/command prompt windows
3. Check each one for the server output

## Quick Solution: Just Restart

If you can't find it, the easiest solution is:

1. **Stop all PHP processes:**
   ```powershell
   Stop-Process -Name php -Force
   ```

2. **Start fresh:**
   ```powershell
   cd D:\XAMPP\htdocs\laravel_image
   D:\xampp\php\php.exe artisan serve
   ```

## Recommended Approach

**Best method for Windows:**
1. Open **Task Manager** (`Ctrl + Shift + Esc`)
2. Go to **Details** tab
3. Find `php.exe` process
4. Check the **Command line** column to see if it shows `artisan serve`
5. Right-click → **End task**
6. Start a new terminal and run the server again

