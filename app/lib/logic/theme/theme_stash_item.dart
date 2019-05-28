import 'package:flutter/material.dart';

abstract class ThemeStashItem {
  final Color primaryColor;
  final Color warningColor;
  final Color accentColor;
  final Color iconColor;
  final Brightness brightness;
  final Color textColorOnScaffold;

  ThemeStashItem({
    @required this.primaryColor,
    @required this.warningColor,
    @required this.accentColor,
    @required this.iconColor,
    @required this.brightness,
    @required this.textColorOnScaffold,
  });

  ThemeData toTheme() {
    return ThemeData(
      primaryColor: primaryColor,
      accentColor: accentColor,
      iconTheme: IconThemeData(color: iconColor),
      brightness: brightness
    );
  }
}

class DefaultTheme extends ThemeStashItem {
  DefaultTheme()
      : super(
          primaryColor: Colors.indigo[800],
          warningColor: Color(0xffFFE000),
          accentColor: Colors.amber[900],
          iconColor: Colors.white,
          brightness: Brightness.light,
          textColorOnScaffold: Colors.black,
        );
}

class DarkTheme extends ThemeStashItem {
  DarkTheme()
      : super(
          primaryColor: Colors.black,
          warningColor: Colors.teal,
          accentColor: Colors.teal,
          iconColor: Colors.white,
          brightness: Brightness.dark,
          textColorOnScaffold: Colors.white,
        );
}

class PinkTheme extends ThemeStashItem {
  PinkTheme()
      : super(
          primaryColor: Colors.purple,
          warningColor: Colors.pink[300],
          accentColor: Colors.pink,
          iconColor: Colors.white,
          brightness: Brightness.light,
          textColorOnScaffold: Colors.black,
        );
}
