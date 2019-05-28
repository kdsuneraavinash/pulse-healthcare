import 'package:flutter/material.dart';
import 'package:pulse_healthcare/logic/theme/theme_stash_item.dart';

class ThemeStash with ChangeNotifier {
  List<ThemeStashItem> get themes => [DefaultTheme(), DarkTheme(), PinkTheme()];

  int currentThemeIndex;

  ThemeStash() {
    currentThemeIndex = 0;
  }

  ThemeStashItem get theme => themes[currentThemeIndex];

  void nextTheme() {
    currentThemeIndex = (currentThemeIndex + 1) % themes.length;
    notifyListeners();
  }
}
