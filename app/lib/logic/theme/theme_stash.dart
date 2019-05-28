import 'package:flutter/material.dart';
import 'package:pulse_healthcare/logic/theme/theme_stash_item.dart';

class ThemeStash with ChangeNotifier {
  List<ThemeStashItem> get themes => [
        ThemeStashItem(
            primaryColor: Colors.indigo[800],
            warningColor: Color(0xffFFE000),
            accentColor: Colors.amber[900],
            iconColor: Colors.white),
        ThemeStashItem(
            primaryColor: Colors.black,
            warningColor: Colors.red[400],
            accentColor: Colors.red[900],
            iconColor: Colors.white),
        ThemeStashItem(
            primaryColor: Colors.purple,
            warningColor: Colors.pink[300],
            accentColor: Colors.pink,
            iconColor: Colors.white),
      ];

  int currentThemeIndex;

  ThemeStash() {
    currentThemeIndex = 0;
  }

  Color get primaryColor => themes[currentThemeIndex].primaryColor;
  Color get warningColor => themes[currentThemeIndex].warningColor;
  Color get accentColor => themes[currentThemeIndex].accentColor;
  Color get iconColor => themes[currentThemeIndex].iconColor;

  void nextTheme() {
    currentThemeIndex = (currentThemeIndex + 1) % themes.length;
    notifyListeners();
  }
}
