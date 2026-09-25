FROM python:3.12-slim

WORKDIR /app

RUN apt-get update && apt-get install -y --no-install-recommends \
    gcc default-libmysqlclient-dev pkg-config \
    && rm -rf /var/lib/apt/lists/*

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY . .

RUN mkdir -p storage

ENV FLASK_APP=wsgi:app
ENV PYTHONUNBUFFERED=1

EXPOSE 5001

CMD ["flask", "--app", "wsgi", "run", "--host", "0.0.0.0", "--port", "5001"]
