<#
.SYNOPSIS
  Verificación del proyecto Bull Racing (el "build" del tema).

.DESCRIPTION
  1. Revisa la sintaxis de todo el PHP del tema, de los plugins bullracing-* y de la OT (carpeta ot/).
  2. Revisa que existan los CSS/JS que carga functions.php.
  3. Revisa que el tema no dependa de servicios externos (debe funcionar sin internet).
  4. Con -ConServidor: comprueba que el sitio local y la API de tarifas respondan.

.EXAMPLE
  .\dev\verificar.ps1
  .\dev\verificar.ps1 -ConServidor
#>
param(
    [switch]$ConServidor,
    [string]$Php = 'C:\xampp81\php\php.exe',
    [string]$UrlSitio = 'http://localhost/bullracing-real'
)

$ErrorActionPreference = 'Stop'
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8
$raiz   = Split-Path -Parent $PSScriptRoot
$tema   = Join-Path $raiz 'wp-content\themes\bullracing'
$errores = 0

function Ok($msg)    { Write-Host "  [OK]    $msg" -ForegroundColor Green }
function Falla($msg) { Write-Host "  [ERROR] $msg" -ForegroundColor Red; $script:errores++ }
function Titulo($msg){ Write-Host "`n$msg" -ForegroundColor Cyan }

# ---------- 1. Sintaxis PHP ----------
Titulo '1. Sintaxis PHP'
if (-not (Test-Path $Php)) {
    Falla "No se encontró PHP en $Php"
} else {
    $carpetas = @($tema) + (Get-ChildItem (Join-Path $raiz 'wp-content\plugins') -Directory -Filter 'bullracing-*' | ForEach-Object FullName)
    $ot = Join-Path $raiz 'ot'
    if (Test-Path $ot) { $carpetas += $ot }
    $archivos = $carpetas | ForEach-Object { Get-ChildItem $_ -Recurse -Filter '*.php' -File }
    foreach ($a in $archivos) {
        $salida = & $Php -l $a.FullName 2>&1
        if ($LASTEXITCODE -ne 0) { Falla ($a.FullName.Replace($raiz, '') + " -> $salida") }
    }
    if ($errores -eq 0) { Ok "$($archivos.Count) archivos PHP sin errores de sintaxis" }
}

# ---------- 2. Recursos que carga functions.php ----------
Titulo '2. Recursos del tema (CSS/JS)'
$functions = Get-Content (Join-Path $tema 'functions.php') -Raw -Encoding UTF8
$skin = if ($functions -match "define\(\s*'BULLRACING_SKIN',\s*'([^']+)'") { $Matches[1] } else { 'base' }
$recursos = [regex]::Matches($functions, "\`$uri \. '(/[^']+)'") | ForEach-Object { $_.Groups[1].Value }
$recursos += "/assets/css/skins/$skin.css"
foreach ($r in ($recursos | Where-Object { $_ -notmatch '/skins/$' } | Sort-Object -Unique)) {
    $ruta = Join-Path $tema ($r.TrimStart('/') -replace '/', '\')
    if (Test-Path $ruta) { Ok $r } else { Falla "No existe $r" }
}

# ---------- 3. Sin dependencias externas ----------
Titulo '3. Funciona sin internet'
$externos = Get-ChildItem $tema -Recurse -Include '*.php','*.css','*.js' -File |
    Select-String -Pattern 'fonts\.googleapis|fonts\.gstatic|cdnjs|cdn\.jsdelivr|unpkg\.com' -SimpleMatch:$false
if ($externos) {
    $externos | ForEach-Object { Falla ("$($_.Path.Replace($raiz, '')):$($_.LineNumber) usa un recurso externo") }
} else {
    Ok 'El tema no carga fuentes ni librerías de internet'
}

# ---------- 4. Servidor local (opcional) ----------
if ($ConServidor) {
    Titulo '4. Servidor local'
    try {
        $r = Invoke-WebRequest -Uri "$UrlSitio/" -UseBasicParsing -TimeoutSec 20
        if ($r.StatusCode -eq 200) { Ok "Sitio local responde ($UrlSitio)" } else { Falla "Sitio local respondió $($r.StatusCode)" }
    } catch { Falla "El sitio local no responde. ¿Están encendidos Apache y MySQL en XAMPP?" }
    try {
        $t = Invoke-RestMethod -Uri "$UrlSitio/wp-json/bullracing/v1/tarifas" -TimeoutSec 20
        Ok "API de tarifas responde: $(@($t).Count) tarifas"
    } catch { Falla 'La API de tarifas no responde. ¿Está activo el plugin Bull Racing Core?' }
}

# ---------- Resultado ----------
Write-Host ''
if ($errores -eq 0) {
    Write-Host 'RESULTADO: TODO BIEN - se puede abrir el Pull Request.' -ForegroundColor Green
    exit 0
} else {
    Write-Host "RESULTADO: $errores problema(s). Corrige antes de abrir el Pull Request." -ForegroundColor Red
    exit 1
}
