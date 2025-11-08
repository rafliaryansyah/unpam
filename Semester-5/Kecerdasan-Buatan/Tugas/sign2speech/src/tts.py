"""Simple TTS helpers for Sign2Speech."""
from __future__ import annotations

import tempfile
from pathlib import Path
from typing import Literal, Optional

from utils.logger import setup_logger


logger = setup_logger()

Provider = Literal["gtts", "pyttsx3"]


def speak_text(text: str, provider: Provider = "gtts", language: str = "id", outfile: Optional[Path] = None) -> Path | None:
    text = text.strip()
    if not text:
        logger.warning("Empty text passed to TTS, skipping")
        return None

    if provider == "pyttsx3":
        return _speak_pyttsx3(text, language)
    return _speak_gtts(text, language, outfile)


def _speak_gtts(text: str, language: str, outfile: Optional[Path]) -> Path:
    from gtts import gTTS
    from playsound import playsound

    if outfile is None:
        tmp_dir = Path(tempfile.gettempdir())
        outfile = tmp_dir / "sign2speech_tts.mp3"

    tts = gTTS(text=text, lang=language)
    tts.save(outfile)
    playsound(str(outfile))
    return outfile


def _speak_pyttsx3(text: str, language: str) -> Path | None:
    try:
        import pyttsx3
    except ImportError as exc:  # pragma: no cover - optional dep
        logger.error("pyttsx3 not installed: %s", exc)
        return None

    engine = pyttsx3.init()
    engine.setProperty("voice", language)
    engine.say(text)
    engine.runAndWait()
    return None
