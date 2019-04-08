import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';

class TimelinePage extends StatelessWidget {
  TimelinePage({Key key}) : super(key: key);

  final double timelineIconLeft = 15;
  final double timelineIconRadius = 20;
  final double timelineItemHeight = 180;

  @override
  Widget build(BuildContext context) {
    return Container(
      child: ListView.builder(
        itemBuilder: (BuildContext context, int index) {
          return Stack(
            children: <Widget>[
              Padding(
                padding: EdgeInsets.only(
                  left: timelineIconLeft * 2 + timelineIconRadius,
                ),
                child: TimeLineCard(
                  timelineItemHeight: timelineItemHeight,
                ),
              ),
              _buildTimeLineVLine(context),
              _buildTimeLineIcon(context)
            ],
          );
        },
        itemCount: 5,
      ),
    );
  }

  Widget _buildTimeLineIcon(BuildContext context) {
    return Positioned(
      top: timelineItemHeight / 2,
      left: timelineIconLeft,
      child: Container(
        color: Theme.of(context).scaffoldBackgroundColor,
        padding: EdgeInsets.symmetric(vertical: 5.0),
        child: CircleAvatar(
          radius: timelineIconRadius,
          backgroundColor: Theme.of(context).primaryColor,
          child: Icon(
            FontAwesomeIcons.prescriptionBottleAlt,
            color: Colors.white,
          ),
        ),
      ),
    );
  }

  Widget _buildTimeLineVLine(BuildContext context) {
    return Positioned(
      top: 0.0,
      bottom: 0.0,
      left: timelineIconLeft + timelineIconRadius,
      child: Container(
        height: double.infinity,
        width: 2.0,
        color: Theme.of(context).primaryColor,
      ),
    );
  }
}

class TimeLineCard extends StatelessWidget {
  final double timelineItemHeight;

  TimeLineCard({Key key, this.timelineItemHeight}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: EdgeInsets.all(20.0),
      child: Container(
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: <Widget>[
              Row(
                crossAxisAlignment: CrossAxisAlignment.baseline,
                textBaseline: TextBaseline.alphabetic,
                children: <Widget>[
                  Text("FEBRUARY 12",
                      style:
                          TextStyle(fontSize: 24, fontWeight: FontWeight.w900)),
                  SizedBox(width: 10),
                  Text("2019",
                      style:
                          TextStyle(fontSize: 16, fontWeight: FontWeight.w800)),
                ],
              ),
              ListTile(
                contentPadding: EdgeInsets.zero,
                trailing: Icon(FontAwesomeIcons.userMd, color: Colors.black),
                title: Text("Dr. Sanath Perera",
                    style: TextStyle(
                        color: Colors.black, fontWeight: FontWeight.w700)),
                subtitle: Text("Prescribed doctor"),
              ),
              Wrap(
                alignment: WrapAlignment.start,
                spacing: 5.0,
                children: <Widget>[
                  keywordChip("Med (2)", context),
                  keywordChip("Report", context),
                  keywordChip("Important", context),
                ],
              )
            ],
          ),
        ),
        width: double.infinity,
        height: timelineItemHeight,
      ),
    );
  }

  Widget keywordChip(String keyword, BuildContext context) {
    return Chip(
      padding: EdgeInsets.zero,
      label: Text(keyword, style: TextStyle(color: Colors.white)),
      backgroundColor: Theme.of(context).accentColor,
    );
  }
}
